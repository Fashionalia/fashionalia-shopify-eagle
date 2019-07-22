<?php declare (strict_types = 1);
namespace Eagle\Repositories;

use Eagle\Models\Order;

class OrderRepository
{
    /** @return Order|null */
    public function getById(string $id)
    {
        return Order::find($id);
    }

    /** @return Order|null */
    public function save(string $id)
    {
        return Order::create(['id' => $id]);
    }
}
