<?php 
    namespace App\Helper;
    use App\Models\Stocks;
    use Illuminate\Http\Request;
    class Cart
    {
        public $items = [];
        public $total_quantity = 0; 
        public $total_amount = 0;
        public function __construct(){
            $this->items = session('cart') ? session('cart') : [];
            $this->total_quantity = $this->get_total_quantity();
            $this->total_amount = $this->get_total_amount();
        }
        public function add($product,$quantity){
            $color = '';
            if($product->colorId == null){
                $item = [
                    'id' => $product->pro->id,
                    'name' => $product->pro->name,
                    'price' =>$product->exportPrice-($product->exportPrice*$product->pro->discount)/100,
                    'image' => $product->pro->image,
                    'size' => $product->size->name,
                    'sizeId' => $product->size->id,
                    'colorId' => null,
                    'color' => '',
                    'quantity' => $quantity
                ];
            }
            if($product->colorId != null){
                $item = [
                    'id' => $product->pro->id,
                    'name' => $product->pro->name,
                    'price' =>$product->exportPrice-($product->exportPrice*$product->pro->discount)/100,
                    'image' => $product->pro->image,
                    'sizeId' => $product->size->id,
                    'size' => $product->size->name,
                    'colorId' => $product->color->id,
                    'color' => $product->color->name,
                    'quantity' => $quantity
                ];
            }
            if(isset($this->items[$product->id])){
                if($this->items[$product->id]['quantity']<$product->importNum){
                    if($quantity>($product->importNum-$this->items[$product->id]['quantity'])){
                        $quantity = $product->importNum-$this->items[$product->id]['quantity'];
                        $this->items[$product->id]['quantity'] += $quantity;
                    }else{
                        $this->items[$product->id]['quantity'] += $quantity;
                    }
                }else{
                    $this->items[$product->id]['quantity'] += 0;
                }
            }else{
                $this->items[$product->id] = $item;
            }
            session(['cart'=>$this->items]);
        }
        public function update($id,$quantity){
            if(isset($this->items[$id])){
                $this->items[$id]['quantity'] = $quantity;
            }
            session(['cart'=>$this->items]);
        }
        public function remove($id){
            if(isset($this->items[$id])){
                unset($this->items[$id]);
            }
            session(['cart'=>$this->items]);
        }
        public function clear(){
            session(['cart'=>'']);
        }
        private function get_total_quantity(){
            $quantity = 0;
            foreach ($this->items as $item) {
                $quantity += $item['quantity'];
            }
            return $quantity;
        }
        private function get_total_amount(){
            $amount = 0;
            foreach ($this->items as $item) {
                $amount += $item['price']*$item['quantity'];
            }
            return $amount;
        }
    }
    

?>