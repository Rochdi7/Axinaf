<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RseSeeder extends Seeder
{
    public function run(): void
    {
        // === 1. Insert Domain ===
        $domainId = DB::table('domains')->insertGetId([
            'name' => 'RSE : ISO 26000',
            'code' => 'rse_iso_26000',
            'description' => 'Responsabilité sociétale selon la norme ISO 26000.',
            'is_active' => true,
            'position' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === Sous-domaine 1 ===
        $sub1Id = DB::table('subdomains')->insertGetId([
            'code' => 'DA1',
            'name' => 'Gouvernance de l’organisation',
            'description' => 'Pratiques de gouvernance responsables.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('questions')->insert([
            [
                'domain_id' => $domainId,
                'subdomain_id' => $sub1Id,
                'code' => 'Q1.1',
                'text' => 'La gouvernance est-elle documentée ?',
                'help_text' => 'Exemples : charte, manuel, organigramme.',
                'position' => 1,
                'weight' => 100,
                'evidence_required' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // === Sous-domaine 2 ===
        $sub2Id = DB::table('subdomains')->insertGetId([
            'code' => 'DA2',
            'name' => 'Devoir de vigilance',
            'description' => 'Mise en place d’un cadre de vigilance.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('questions')->insert([
            [
                'domain_id' => $domainId,
                'subdomain_id' => $sub2Id,
                'code' => 'Q2.1',
                'text' => 'Avez-vous mis en place une politique de vigilance ?',
                'help_text' => null,
                'position' => 1,
                'weight' => 100,
                'evidence_required' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // === Sous-domaine 3 ===
        $sub3Id = DB::table('subdomains')->insertGetId([
            'code' => 'DA3',
            'name' => 'Situations présentant un risque pour les droits de l’Homme',
            'description' => 'Identification et évaluation des risques.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('questions')->insert([
            [
                'domain_id' => $domainId,
                'subdomain_id' => $sub3Id,
                'code' => 'Q2.2',
                'text' => 'Les risques sont-ils identifiés et évalués ?',
                'help_text' => 'Cartographie, analyse de risques, audits internes.',
                'position' => 1,
                'weight' => 100,
                'evidence_required' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // === Sous-domaine 4 ===
        $sub4Id = DB::table('subdomains')->insertGetId([
            'code' => 'DA4',
            'name' => 'Prévention de la complicité',
            'description' => 'Mécanismes pour éviter toute complicité avec des atteintes aux droits.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('questions')->insert([
            [
                'domain_id' => $domainId,
                'subdomain_id' => $sub4Id,
                'code' => 'Q2.3',
                'text' => 'Des mécanismes existent-ils pour éviter la complicité ?',
                'help_text' => 'Code de conduite, due diligence sur les partenaires.',
                'position' => 1,
                'weight' => 100,
                'evidence_required' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
