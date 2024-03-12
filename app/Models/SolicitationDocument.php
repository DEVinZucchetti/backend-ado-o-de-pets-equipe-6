<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitationDocument extends Model
{
    use HasFactory;
    use HasUuids; //Para gerar o uuid automatico


    protected $table = "solicitations_documents"; //para o código não ter dúvidas em relação ao nome da tabela

    protected $fillable = ['client_id', 'cpf', 'document_address', 'rg', 'term_adoption'];
}
