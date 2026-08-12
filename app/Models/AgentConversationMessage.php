<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentConversationMessage extends Model
{
    public function conversation()
    {
        return $this->belongsTo(AgentConversation::class);
    }
}
