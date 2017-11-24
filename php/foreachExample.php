<?
    class Entree{
        public $name;
        public $ingredients;
        
        public function__construct($name,$ingredients){
            this->ingredients = $ingredients;
            this->name = $name;
        }
        
        public function hasIngredient($ingredient){
            return in_array($ingredient, $this->ingredients);
        }
        
        public function getSize(){
            return array('소','중','대');
        }
    }
    $soup = new Entree;
    $soup->name='닭고기 스프';
    $soup->ingredients = array('닭고기','물');

    $sandwich = new Entree;
    $sandwich->name = '닭고기 샌드위치';
    $sandwich->ingredients = array('참치','우유');

    foreach(['닭고기','참치','물','우유'] as $ing){
        $var = "\n";
        if($soup->hasIngredient($ing)){
            print "수프의 재료: $ing. $var";
        }
        if($sandwich->hasIngredient($ing)){
            print "샌드위치의 재료: $ing. \n";
        }
    }
    $var = Entree::getsize();
    foreach($var as $var){
        print "$var\n";
    }

    
?>
