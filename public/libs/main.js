    $('map').imageMapResize();
    Highcharts.setOptions({
        lang: {
            months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            downloadJPEG: "Télécharger image JPEG",
            downloadPDF: "Télécharger image PDF",
            downloadPNG: "Télécharger image PNG",
            downloadSVG: "Télécharger document SVF",
            printChart: "Imprimer image"
        },
        credits: {
            enabled: true,
            style: {
                cursor: 'pointer',
                color: '#ccc',
                fontSize: '12px'
            },
            text: 'Source : SSE PDR Souss Massa',
            href: 'https://www.soussmassa.ma/fr'
        }
    });
    {{ chart(chart1) }}
    {{ chart(chart2) }}
    {{ chart(chart3) }}
    {{ chart(chart4) }}
    {{ chart(chart5) }}
    {{ chart(chart6) }}
    {{ chart(chart7) }}
