<?php
    namespace App\Enum;
    enum DemandeEnum:string{
        case ATTESTATION_DE_INSCRIPTION='ATTESTATION_DE_INSCRIPTION';
        case ATTESTATION_DE_PRESENCE='ATTESTATION_DE_PRESENCE';
        case FEUILLES_DE_STAGE='FEUILLES_DE_STAGE';
        case FEUILLES_DE_NOTES='FEUILLES_DE_NOTES';
        case AUTRES='AUTRES';
    }
