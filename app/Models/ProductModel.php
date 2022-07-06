<?php namespace App\Models;
  
use CodeIgniter\Model;
  
class ProductModel extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'product_name';
    protected $allowedFields = ['product_price','otp','mobile'];

    /* protected $validationRules = [
        'product_name'     => 'required|alpha_numeric_space|is_unique[product.product_name]',
        'product_price'        => 'required|numeric',
    ];
    protected $validationMessages = [
        'product_name' => [
            'is_unique' => 'Sorry. That product name has already been taken. Please choose another.',
        ],
        'product_price' => [
            'numeric' => 'Sorry. That product price can only be numbers.',
        ],
    ]; */
}