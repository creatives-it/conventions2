



/////////////////////////////////////////////////////////////////////////////////////////
ALTER TABLE projet ADD address VARCHAR(255) DEFAULT NULL, ADD locality VARCHAR(255) DEFAULT NULL, ADD country VARCHAR(255) DEFAULT NULL, ADD lat DOUBLE PRECISION DEFAULT NULL, ADD lng DOUBLE PRECISION DEFAULT NUL;

CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, deleted_at DATETIME DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, locality VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, lat DOUBLE PRECISION DEFAULT NULL, lng DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

// cherche partenaires en double
SELECT `name`, count(`name`) FROM `partenaire` GROUP BY `name` HAVING count(`name`) > 1
// affiche id des doublons
select * FROM partenaire p INNER JOIN (SELECT `name`, count(`name`) FROM `partenaire` GROUP BY `name` HAVING count(`name`) > 1) b ON p.name = b.name ORDER BY p.name DESC ;

UPDATE convention_partenaire SET partenaire_id = 70 WHERE partenaire_id = 177;
UPDATE convention_contribution SET partenaire_id = 70 WHERE partenaire_id = 177;
UPDATE convention_engagement SET partenaire_id = 70 WHERE partenaire_id = 177;
UPDATE convention_signature SET partenaire_id = 70 WHERE partenaire_id = 177;
UPDATE convention SET maitre_ouvrage_id = 70 WHERE maitre_ouvrage_id = 177;
UPDATE convention SET maitre_ouvrage2_id = 70 WHERE maitre_ouvrage2_id = 177;

UPDATE convention_partenaire SET partenaire_id = 184 WHERE partenaire_id = 185;
UPDATE convention_contribution SET partenaire_id = 184 WHERE partenaire_id = 185;
UPDATE convention_engagement SET partenaire_id = 184 WHERE partenaire_id = 185;
UPDATE convention_signature SET partenaire_id = 184 WHERE partenaire_id = 185;
UPDATE convention SET maitre_ouvrage_id = 184 WHERE maitre_ouvrage_id = 185;
UPDATE convention SET maitre_ouvrage2_id = 184 WHERE maitre_ouvrage2_id = 185;

UPDATE convention_partenaire SET partenaire_id = 179 WHERE partenaire_id = 180;
UPDATE convention_contribution SET partenaire_id = 179 WHERE partenaire_id = 180;
UPDATE convention_engagement SET partenaire_id = 179 WHERE partenaire_id = 180;
UPDATE convention_signature SET partenaire_id = 179 WHERE partenaire_id = 180;
UPDATE convention SET maitre_ouvrage_id = 179 WHERE maitre_ouvrage_id = 180;
UPDATE convention SET maitre_ouvrage2_id = 179 WHERE maitre_ouvrage2_id = 180;

UPDATE convention_partenaire SET partenaire_id = 206 WHERE partenaire_id = 207;
UPDATE convention_contribution SET partenaire_id = 206 WHERE partenaire_id = 207;
UPDATE convention_engagement SET partenaire_id = 206 WHERE partenaire_id = 207;
UPDATE convention_signature SET partenaire_id = 206 WHERE partenaire_id = 207;
UPDATE convention SET maitre_ouvrage_id = 206 WHERE maitre_ouvrage_id = 207;
UPDATE convention SET maitre_ouvrage2_id = 206 WHERE maitre_ouvrage2_id = 207;

update partenaire set deleted_at="2022-04-20 00:00:01" where id in (207,180,185,117);
// a supprimer
177 	وزارة الصحة
185 	وزارة التربية الوطنية والتكوين المهني
180 	الوزارة المنتدبة المكلفة بالماء
183 	المجلس الإقليمي للحسيمة

// a garder
70 	وزارة الصحة
184 	وزارة التربية الوطنية والتكوين المهني
179 	الوزارة المنتدبة المكلفة بالماء
56 	المجلس الإقليمي للحسيمة

INSERT INTO `convention_signature` (`id`, `convention_id`, `partenaire_id`, `date_transmission_pour_signature`, `numero_envoi_depart`, `rappel1`, `rappel2`, `date_courrier_arrive`, `numero_courrier_arrive`, `observations`, `is_signee`, `deleted_at`)
VALUES (NULL, '2', '223', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL);