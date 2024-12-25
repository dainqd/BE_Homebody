<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        "og_title", "og_des", "og_url", "og_site", "og_img", "brand_name", "home_name",
        "qna_email", "browser_title", "meta_tag", "meta_keyword", "domain_url",
        "email", "phone", "address", "address_detail", "zip", "logo", "favicon", "thumbnail",
        "owner_name", "owner_phone", "owner_email", "fax", "business_number",
    ];
}
