<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        Faq::insert([
            [
                'question' => 'Se ho subito una linfoadenectomia (rimozione totale dei linfonodi) posso fare i prelievi ematici in quel braccio?',
                'answer' => "Si, a condizione che vengano seguite le buone norme di pratica infermieristica nell’esecuzione del prelievo. Il rischio di sviluppare linfedema dopo prelievo è ridotto, tuttavia per prudenza è preferibile effettuare i prelievi nell’arto controlaterale.Se nel braccio sano vengono fatte le infusioni, o se in quest’ultimo è difficile reperire le vene, i prelievi ematici possono essere eseguiti, anche in presenza di linfedema conclamato."
            ],
            [
                'question' => 'Posso fare le infusioni chemioterapiche nel braccio senza linfonodi?',
                'answer' => 'È sconsigliabile farlo perché il rischio di sviluppare linfedema può aumentare.'
            ],
            [
                'question' => 'Se ho subito una linfoadenectomia (rimozione totale dei linfonodi) posso misurare la pressione dal lato operato o la pressione applicata può provocare danni al sistema linfatico?',
                'answer' => 'Puoi farlo tranquillamente, la misurazione della pressione non crea alcun danno ai vasi linfatici, pressioni molto più elevate e mantenute nel tempo in alcuni rari casi hanno creato linfedema.'
            ],
            [
                'question' => 'Se ho subito la rimozione totale dei linfonodi posso prendere l’aereo?',
                'answer' => 'Puoi prendere l’aereo tranquillamente, i voli aerei, anche di lunga percorrenza, non presentano rischi significativi per la comparsa di linfedema.'
            ],
            [
                'question' => 'Durante un viaggio in aereo è consigliabile indossare una guaina compressiva preventiva?',
                'answer' => 'Se non si ha linfedema non è consigliabile utilizzare una guaina preventiva, invece in caso di linfedema va utilizzata la guaina come d’abitudine.'
            ],
            [
                'question' => "Quando mi riposo mantenere l'arto superiore sollevato sopra la testa serve a favorire il drenaggio linfatico?",
                'answer' => 'Mantenere forzatamente l’arto sollevato non migliora il drenaggio linfatico ma può favorire danni muscolo-tendinei alla spalla.'
            ],
            [
                'question' => 'Posso andare al mare dopo rimozione totale o parziale dei linfonodi ascellari?',
                'answer' => 'Sì puoi, è opportuno però evitare le ore più calde per non surriscaldare l’arto. Oltre i 41°C l’attività del sistema linfatico si ferma. È consigliabile rinfrescarsi in acqua e, nelle ore più calde, non restare sotto l’ombrellone ma spostarsi in ambienti più freschi. Ricorda che creme solari e teli riparano da scottature ma non dal calore.'
            ],
            [
                'question' => 'Posso prendere il sole, se ho subito una linfoadenectomia o ho linfedema?',
                'answer' => 'Sì, avendo cura di non surriscaldare l’arto: puoi rinfrescare l\'arto facendo il bagno in acqua o la doccia fresca.'
            ],
            [
                'question' => 'Posso andare in spa?',
                'answer' => 'La sauna e il bagno turco vanno limitati come numero e come durata temporale, cercando di non superare la temperatura di 41°C. Una volta usciti è consigliabile fare una doccia fresca. '
            ],
            [
                'question' => 'Posso applicare ghiaccio all’arto che ha subito una linfoadenectomia o che ha linfedema?',
                'answer' => 'Evita il raffreddamento eccessivo dell’arto, al di sotto dei 22°C il flusso linfatico si arresta.'
            ],
            [
                'question' => 'Posso fare il bagno caldo?',
                'answer' => 'I bagni caldi sono consigliabili con temperature fino a 37-38° C.'
            ],
            [
                'question' => 'Posso fare attività fisica/ sforzi fisici con il braccio operato?',
                'answer' => 'Sì, superato l’immediato post operatorio una regolare attività fisica riduce significativamente il rischio di sviluppare linfedema. Nessuna attività sportiva è specificatamente vietata. È consigliabile evitare sollecitazioni (colpi) all\'arto operato, per evitare di traumatizzare il sistema linfatico residuo. È importante una ripresa graduale dell’attività sportiva.'
            ],
            [
                'question' => 'Se resto molto in piedi mi si gonfia la mano, come posso fare?',
                'answer' => 'Se sono fermo in piedi posso tenere una pallina di gommapiuma in mano e schiacciarla. Se cammino è consigliabile muovere le braccia, questo aiuta la circolazione.'
            ],
            [
                'question' => 'È vero che devo prestare attenzione a non ingrassare?',
                'answer' => 'Sì, meglio controllare il peso, l\'accumulo di adipe rallenta il circolo linfatico e comprime i vasi.'
            ],
            [
                'question' => 'È vero che devo prestare attenzione alle infezioni nel braccio operato di linfoadenectomia?',
                'answer' => 'Sì, se la funzione linfatica è ridotta il trasporto dei germi e l’attivazione delle nostre difese immunitarie avviene lentamente, in questo modo aumenta il rischio di sviluppare infezioni. Le infezioni potrebbero portare ad un danno aggiuntivo al sistema linfatico, determinando la comparsa o il peggioramento del linfedema.'
            ],
            [
                'question' => 'Come posso evitare infezioni?',
                'answer' => 'Se ti tagli (anche un piccolo taglio) disinfetta la ferita. Utilizza i guanti ogni qualvolta devi svolgere attività che possano essere a rischio di ferite, o irritazioni della cute (giardinaggio, lavare le stoviglie, pentole bollenti, forno, pulizie con detergenti chimici). Evita le punture di insetto, in particolare, in estate, delle zanzare. Se fai una passeggiata in campagna indossa una maglia a manica lunga e utilizza spray repellenti a base naturale. Evita graffi o morsi di gatti e cani.'
            ],
            [
                'question' => 'Mi sono tagliato/scottato/sono stato graffiato dal mio gatto come mi devo comportare?',
                'answer' => 'Può capitare, non ti allarmare! Disinfetta subito la ferita, nelle ore successive osserva che non insorga un arrossamento o gonfiore importante, che il dolore non aumenti eccessivamente o che compaia qualche linea di febbre, in questo caso rivolgiti al tuo medico.'
            ],
            [
                'question' => 'Devo avere particolare cura della cute?',
                'answer' => 'Sì, la nostra pelle è la più importante difesa dai germi. Usa prodotti a pH neutro per aiutare la pelle a mantenere il suo naturale film protettivo, asciugati bene e idrata la pelle per renderla morbida e senza screpolature.'
            ],
            [
                'question' => 'Posso fare la manicure?',
                'answer' => 'Sì, fai però attenzione ad usare strumenti igienizzati, è consigliabile non rimuovere le cuticole delle unghie per evitare piccole lesioni. Puoi tranquillamente utilizzare lo smalto.'
            ],
            [
                'question' => 'Posso depilarmi l\'ascella?',
                'answer' => 'La ceretta è sconsigliata per il rischio di lesioni e infezioni. Anche la depilazione con rasoi e lamette non è consigliata. L’epilazione definitiva con laser e luce pulsata, eseguito presso centri medicali, non è controindicata.'
            ],
            [
                'question' => 'Come posso accorgermi di avere un iniziale linfedema?',
                'answer' => 'Puoi avere un linfedema iniziale se hai: sensazione di pesantezza (spesso nella regione mediale del gomito), gonfiore che scompare dopo il riposo notturno ma che ricompare durante il giorno. In questi casi è importante fare una visita fisiatrica. Il linfedema iniziale è in una fase spontaneamente regressiva, è importante intervenire il prima possibile.'
            ],
            [
                'question' => 'Se ho gonfiore al seno e all’ascella cosa posso fare?',
                'answer' => 'Indossa indumenti adatti compressivi, senza cuciture strette ma ideati appositamente per favorire la circolazione (prescritti dal medico).'
            ],
            [
                'question' => 'Posso fare massaggi linfodrenanti per prevenire il linfedema?',
                'answer' => 'No, il linfodrenaggio non previene il linfedema ma non è controindicato. Per prevenire il linfedema è importante: la corretta igiene e idratazione della cute, prevenire le infezioni, controllare il peso, svolgere attività motoria regolarmente, evitare il surriscaldamento dell’arto.'
            ]
        ]);
    }
}
