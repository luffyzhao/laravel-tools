<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/5
 * Time: 21:50
 */

namespace LTools\Contracts\Signer;


interface SignerInterface
{
    public function getAuthIdentifier();
}