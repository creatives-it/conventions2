<?php
/*
in twig.yaml twig.globals : settings: '@App\Service\Settings'
init_settings in default controller
afficher dans templates twig {{ dump(settings.get('exercice')) }}
dans controller :
    titre action (Settings $settings)
    modifier    $settings->set('exercice','2022','entity');
    get         $settings->get('exercice','2022');
in admin class:
    private $settings;
    public function __construct($code, $class, $baseControllerName, Settings $settings)
    {
    parent::__construct($code, $class, $baseControllerName);
    $this->settings = $settings;
    }
    $numero =$this->settings->get('numero','0');

 */
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;

class Settings
{
    /** @var \PDO */
    private $db;

    public function __construct(EntityManagerInterface $em)    {
        $this->db = $em->getConnection();
    }

    /**
    Gets a setting value.
    If the setting doesn't exist, returns the default value specified as second param
     */
    public function get(string $name, $default=''): string    {
        $stmt = $this->db->prepare("SELECT `value` FROM `settings` WHERE `name`=?;");
        $stmt->execute([$name]);
        return $stmt->fetchColumn() ?: $default;
    }

    /**
    Gets a setting value.
    If the setting doesn't exist, returns the default value specified as second param
     */
    public function getType(string $name, $default=''): string    {
        $stmt = $this->db->prepare("SELECT `type` FROM `settings` WHERE `name`=?;");
        $stmt->execute([$name]);
        return $stmt->fetchColumn() ?: $default;
    }

    /**
    Gets a setting id for entityType.
     */
    public function getIdEntity(string $name, $default=0): string    {
        $value = $this->get($name);
        $type = $this->getType($name);
        $stmt = $this->db->prepare("SELECT `id_entity` FROM `settings` WHERE `name`=?;");
        $stmt->execute([$name]);
        $id = $stmt->fetchColumn();
//        dump($stmt);
//        dump($stmt->execute([$name]));
//        dump($id);die;
        /**/
        if (empty($id) and (!empty($value)) and ($type=='entity')) {
            $stmt2 = $this->db->prepare("SELECT `entity` FROM `settings` WHERE `name`=?;");
            $stmt2->execute([$name]);
            $entity = $stmt2->fetchColumn();
//            dump($stmt2);
//            dump($stmt2->execute([$name]));
//            dump($entity);
            //die;
            if (!empty($entity)){
                $stmt4 = $this->db->prepare("SELECT Max(id) FROM ".$entity.";");
                $stmt4->execute();
                $defaultId = $stmt4->fetchColumn() ?: $default;
//                dump($stmt4);
//                dump($stmt4->execute());
//                dump($defaultId);
//                //die;
                $field = 'libelle';
                if (in_array($entity,['fonctionnaire','statut_fonctionnaire'])) $field = 'nom';
                $stmt3 = $this->db->prepare("SELECT `id` FROM ".$entity." WHERE `".$field."`=?;");
                $stmt3->execute([$value]);
                $id = $stmt3->fetchColumn() ?: $defaultId;
//                dump($stmt3);
//                dump($stmt3->execute([$value]));
//                dump($stmt3->fetchColumn());
//                dump($id);
//                die;
            }
        }
        return   (!empty($id)) ? $id : $default;

    }

    /**
     * Sets a setting value.
     * If the setting doesn't exists, it creates it. Otherwise, it replaces the db value
     */
    public function set(string $name, string $value, string $type = '', int $locked = 0, string $entity = '', string $idEntity = null, string $description = '')    {
        $this->db->prepare("INSERT INTO settings (name, value, type, locked, entity, id_entity, description) VALUES (?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE value=?;")
            ->execute([$name, $value, $type, $locked, $entity, $idEntity, $description, $value]);
    }

}