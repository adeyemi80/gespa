<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class SidebarMenu extends Component
{
    public array $showMenu = [
        'eleves' => false,
        'bulletins' => false,
        'classes' => false,
        'notes' => false,
        'professeurs' => false,
        'annees' => false,
    ];

    public function toggleMenu($menu)
    {
        $this->showMenu[$menu] = !$this->showMenu[$menu];
    }

    public function render()
    {
        return view('livewire.layout.sidebar-menu');
    }
}