<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
    use HasFactory;
    protected $fillable=["key","value","slug"];
    protected $encrypts = ['value'];

      public function getAttribute ($key) {
        $value = parent::getAttribute ($key);
        if (in_array ($key, $this->encrypts)) {
          try {
            return Crypt::decryptString ($value);
          } catch(Exception $e)
          {
            return $value;
          }
        }
      }

      public function setAttribute ($key, $value) {
        if (in_array ($key, $this->encrypts)) {
          try {
            $v = Crypt::encryptString ($value);
            parent::setAttribute ($key, $v);
          } catch(Exception $e) {
            parent::setAttribute ($key, $value);}
        }

      }
}
