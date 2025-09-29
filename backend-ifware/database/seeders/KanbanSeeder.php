<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KanbanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criar Usuários com Funções
        $admin = User::create([
            'name' => 'Admin Ifware',
            'email' => 'admin@ifware.com.br',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $user1 = User::create([
            'name' => 'Funcionário 1',
            'email' => 'user1@ifware.com.br',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $user2 = User::create([
            'name' => 'Funcionário 2',
            'email' => 'user2@ifware.com.br',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // 2. Criar Tipos de Projeto
        $devType = ProjectType::create(['nomeTipoProjeto' => 'Desenvolvimento']);
        $supType = ProjectType::create(['nomeTipoProjeto' => 'Suporte']);

        // 3. Criar Clientes
        $clientA = Client::create(['nomeCliente' => 'Empresa Cliente A', 'emailContato' => 'contato@clientea.com']);
        $clientB = Client::create(['nomeCliente' => 'Empresa Cliente B', 'emailContato' => 'contato@clienteb.com']);

        // 4. Criar Projetos
        $projectA_dev = Project::create([
            'nomeProjeto' => 'Novo Sistema de E-commerce',
            'clienteId' => $clientA->id,
            'tipoProjetoId' => $devType->id,
        ]);

        $projectA_sup = Project::create([
            'nomeProjeto' => 'Manutenção Legado',
            'clienteId' => $clientA->id,
            'tipoProjetoId' => $supType->id,
        ]);

        $projectB_dev = Project::create([
            'nomeProjeto' => 'Aplicativo Mobile',
            'clienteId' => $clientB->id,
            'tipoProjetoId' => $devType->id,
        ]);

        // 5. Criar Tarefas e Atribuí-las
        // Tarefas para o Projeto A (Desenvolvimento)
        $task1 = Task::create([
            'tituloTask' => 'Estruturar o banco de dados',
            'descricaoTask' => 'Modelar e criar todas as tabelas necessárias para o novo e-commerce.',
            'status' => 'Em Andamento',
            'projetoId' => $projectA_dev->id,
        ]);
        $task2 = Task::create([
            'tituloTask' => 'Desenvolver tela de checkout',
            'descricaoTask' => 'Implementar a interface e a lógica do carrinho de compras.',
            'status' => 'A Fazer',
            'projetoId' => $projectA_dev->id,
        ]);
        $task3 = Task::create([
            'tituloTask' => 'Configurar API de pagamento',
            'descricaoTask' => 'Integrar com o gateway de pagamento X.',
            'status' => 'A Fazer',
            'projetoId' => $projectA_dev->id,
        ]);

        // Tarefas para o Projeto A (Suporte)
        $task4 = Task::create([
            'tituloTask' => 'Corrigir bug no login',
            'descricaoTask' => 'Usuários estão relatando erro 500 ao tentar logar no sistema antigo.',
            'status' => 'A Fazer',
            'projetoId' => $projectA_sup->id,
        ]);

        // Tarefas para o Projeto B (Desenvolvimento)
        $task5 = Task::create([
            'tituloTask' => 'Criar protótipo das telas',
            'descricaoTask' => 'Desenhar o fluxo do usuário no Figma.',
            'status' => 'Concluído',
            'projetoId' => $projectB_dev->id,
        ]);

        // 6. Atribuir tarefas aos usuários
        $task1->assignedUsers()->attach([$user1->id, $user2->id]); // Tarefa 1 para os dois funcionários
        $task2->assignedUsers()->attach($user1->id); // Tarefa 2 apenas para o funcionário 1
        $task4->assignedUsers()->attach($user2->id); // Tarefa 4 apenas para o funcionário 2
        $task5->assignedUsers()->attach($user1->id); // Tarefa 5 para o funcionário 1
    }
}