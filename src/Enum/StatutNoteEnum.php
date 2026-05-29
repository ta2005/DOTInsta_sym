<?php
namespace App\Enum;

enum StatutNoteEnum: string
{
    case EN_ATTENTE = 'EN_ATTENTE';
    case CORRIGE = 'CORRIGE';
    case VERIFIE = 'VERIFIE';
    case CONTESTE = 'CONTESTE';
}
