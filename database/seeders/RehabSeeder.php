<?php

namespace Database\Seeders;

use App\Models\RehabExtra;
use App\Models\RehabPeriod;
use App\Models\RehabQuestion;
use Illuminate\Database\Seeder;

class RehabSeeder extends Seeder
{
    public function run(): void
    {
        // PERIODO 0
        $periodo0 = RehabPeriod::create([
            'title' => 'Movimento non consentito',
            'order' => 0,
            'p_number' => 0,
            'description' => 'Durante questo periodo fai questi semplici 3 esercizi finchè il chirurgo non ti darà libertà di movimento',
            'video_youtube_id' => 'qo-HMcOgMWI',
        ]);

        RehabQuestion::create([
            'rehab_period_id' => $periodo0->id,
            'question' => 'Quanto dolore hai percepito durante la sessione?',
            'title' => 'DOLORE',
            'type' => 'scale',
            'labels' => ['Nessun dolore', 'Sopportabile', 'Molto male'],
            'block_if' => ['type' => 'scale', 'greater_than' => 6],
        ]);

        RehabQuestion::create([
            'rehab_period_id' => $periodo0->id,
            'question' => 'Il chirurgo ti ha dato la possibilità di muovere il braccio?',
            'title' => '',
            'type' => 'choice',
            'options' => ['Sì', 'No'],
            'block_if' => ['type' => 'choice', 'equals' => 'No'],
        ]);

        // FINE PERIODO 0

        // PERIODO 1
        $periodo1 = RehabPeriod::create([
            'title' => 'Con limitazioni di movimento',
            'order' => 1,
            'p_number' => 1,
            'description' => 'Primi esercizi con limitazione di movimento',
            'video_youtube_id' => 'aPCve2sXlKw',
        ]);

        RehabQuestion::create([
            'rehab_period_id' => $periodo1->id,
            'question' => 'Quanto dolore hai percepito durante la sessione?',
            'title' => 'DOLORE',
            'type' => 'scale',
            'labels' => ['Nessun dolore', 'Sopportabile', 'Molto male'],
            'block_if' => ['type' => 'scale', 'greater_than' => 6],
        ]);


        RehabQuestion::create([
            'rehab_period_id' => $periodo1->id,
            'question' => 'Riesci a portare la mano alla fronte?',
            'title' => 'ELASTICITÁ',
            'type' => 'choice',
            'options' => ['Sì', 'Con difficoltà', 'No'],
            'block_if' => ['type' => 'choice', 'equals' => 'No'],
        ]);


        RehabQuestion::create([
            'rehab_period_id' => $periodo1->id,
            'question' => 'Il chirurgo ti ha dato la possibilità di mobilità totale del braccio?',
            'title' => 'ELASTICITÁ',
            'type' => 'choice',
            'options' => ['Sì', 'No'],
            'block_if' => ['type' => 'choice', 'equals' => 'No'],
        ]);

        // FINE PERIODO 1

        // PERIODO 1BIS
        $periodo1bis = RehabPeriod::create([
            'title' => 'Senza limitazioni di movimento',
            'order' => 2,
            'p_number' => 1,
            'description' => 'Primi esercizi senza limitazione di movimento',
            'video_youtube_id' => 'Rhpuvk_fsoI',
        ]);

        RehabQuestion::create([
            'rehab_period_id' => $periodo1bis->id,
            'question' => 'Quanto dolore hai percepito durante la sessione?',
            'title' => 'DOLORE',
            'type' => 'scale',
            'labels' => ['Nessun dolore', 'Sopportabile', 'Molto male'],
            'block_if' => ['type' => 'scale', 'greater_than' => 6],
        ]);

        RehabQuestion::create([
            'rehab_period_id' => $periodo1bis->id,
            'question' => 'Riesci a portare la mano sopra la testa?',
            'title' => 'ELASTICITÁ',
            'type' => 'choice',
            'options' => ['Sì', 'Con difficoltà', 'No'],
            'block_if' => ['type' => 'choice', 'equals' => 'No'],
        ]);

        RehabQuestion::create([
            'rehab_period_id' => $periodo1bis->id,
            'question' => 'Riesci a portare la mano dietro la schiena sopra al pantalone?',
            'title' => 'ELASTICITÁ',
            'type' => 'choice',
            'options' => ['Sì', 'Con difficoltà', 'No'],
            'block_if' => ['type' => 'choice', 'equals' => 'No'],
        ]);
        // FINE PERIODO 1BIS

        // PERIODO 2
        $periodo2 = RehabPeriod::create([
            'title' => 'Fase intermedia',
            'order' => 3,
            'p_number' => 2,
            'description' => 'Fase intermedia della riabilitazione',
            'video_youtube_id' => 'b8dzgb6CynQ',
        ]);

        RehabQuestion::create([
            'rehab_period_id' => $periodo2->id,
            'question' => 'Quanto dolore hai percepito durante la sessione?',
            'title' => 'DOLORE',
            'type' => 'scale',
            'labels' => ['Nessun dolore', 'Sopportabile', 'Molto male'],
            'block_if' => ['type' => 'scale', 'greater_than' => 6],
        ]);

        RehabQuestion::create([
            'rehab_period_id' => $periodo2->id,
            'question' => 'Riesci a farti la coda con entrambe le mani?',
            'title' => 'ELASTICITÁ',
            'type' => 'choice',
            'options' => ['Sì', 'Con difficoltà', 'No'],
            'block_if' => ['type' => 'choice', 'equals' => 'No'],
        ]);

        RehabQuestion::create([
            'rehab_period_id' => $periodo2->id,
            'question' => 'Riesci ad allacciarti il reggiseno?',
            'title' => 'ELASTICITÁ',
            'type' => 'choice',
            'options' => ['Sì', 'Con difficoltà', 'No'],
            'block_if' => ['type' => 'choice', 'equals' => 'No'],
        ]);
        // FINE PERIODO 2

        // PERIODO 3
        $periodo3 = RehabPeriod::create([
            'title' => 'Fase finale',
            'order' => 4,
            'p_number' => 3,
            'description' => 'Fase finale della riabilitazione',
            'video_youtube_id' => 'MtP9I8ACrGg',
        ]);

        RehabQuestion::create([
            'rehab_period_id' => $periodo3->id,
            'question' => 'Quanto dolore hai percepito durante la sessione?',
            'title' => 'DOLORE',
            'type' => 'scale',
            'labels' => ['Nessun dolore', 'Sopportabile', 'Molto male'],
            'block_if' => ['type' => 'scale', 'greater_than' => 6],
        ]);

        RehabQuestion::create([
            'rehab_period_id' => $periodo3->id,
            'question' => 'Riesci a prendere i barattoli dai ripiani in alto o mettere i piatti nello scola piatti in alto?',
            'title' => 'ELASTICITÁ',
            'type' => 'choice',
            'options' => ['Sì', 'Con difficoltà', 'No'],
            'block_if' => ['type' => 'choice', 'equals' => 'No'],
        ]);
        // FINE PERIODO 3

        // EXTRA1
        $extras1 = RehabExtra::create([
            'title' => 'Massaggio cicatriziale',
            'order' => 1,
            'description' => "Metodo per migliorare l'elasticità della pelle cicatrizzata.",
            'video_youtube_id' => 'eVMdbZLwslE',
        ]);

        // EXTRA2
        $extras2 = RehabExtra::create([
            'title' => 'Auto Massaggio Drenante',
            'order' => 1,
            'description' => "Tecnica per il drenaggio linfatico dopo l'intervento.",
            'video_youtube_id' => 'YPDhCjeoDfE',
        ]);
    }
}
