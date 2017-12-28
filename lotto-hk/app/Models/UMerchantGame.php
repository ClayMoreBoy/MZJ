<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UMerchantGame extends Model
{
    //

    public function listItems()
    {
        switch ($this->id) {
            case UGame::k_type_te_solo:
            case UGame::k_type_all_solo:
//            case UGame::k_type_all_two:
//            case UGame::k_type_all_three:
//            case UGame::k_type_all_four:
//            case UGame::k_type_all_five:
//            case UGame::k_type_all_six:
            {
                return ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10',
                    '11', '12', '13', '14', '15', '16', '17', '18', '19', '20',
                    '21', '22', '23', '24', '25', '26', '27', '28', '29', '30',
                    '31', '32', '33', '34', '35', '36', '37', '38', '39', '40',
                    '41', '42', '43', '44', '45', '46', '47', '48', '49'];
            }
            case UGame::k_type_all_zodiac:
//            case UGame::k_type_all_zodiac_five:
//            case UGame::k_type_all_zodiac_four:
//            case UGame::k_type_all_zodiac_six:
//            case UGame::k_type_all_zodiac_three:
//            case UGame::k_type_all_zodiac_two:
            {
                return ['鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪'];
            }
            default: {
                return [];
            }
        }
    }

    public function oddName()
    {
        switch ($this->id) {
            case UGame::k_type_all_solo: //平特单码
            case UGame::k_type_all_zodiac: {//平特单肖
                return '最高赔率';
            }
            default:
                return '固定赔率';
        }
    }

    public function bonusName()
    {
        switch ($this->id) {
            case UGame::k_type_all_solo: //平特单码
            case UGame::k_type_all_zodiac: {//平特单肖
                return '最高返还';
            }
            default:
                return '固定返还';
        }
    }
}
