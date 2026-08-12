<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentConversation extends Model
{
    public function messages()
    {
        return $this->hasMany(AgentConversationMessage::class);
    }
}
