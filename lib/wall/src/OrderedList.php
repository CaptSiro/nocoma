<?php

  require_once __DIR__ . "/JSONEncodeAble.php";

  class OrderedListNode extends JSONEncodeAble {
    public $orderBy, $content;

    public function __construct ($orderBy, $content) {
      $this->orderBy = $orderBy;
      $this->content = $content;
    }
  }

  class OrderedList implements Iterator, JsonSerializable {
    public $list = [];
    private $orderFN;
    public function setOrderFN (Closure $fn) {
      $this->orderFN = $fn;
    }

    public static function asc () {
      return function (int $inserting, int $current): bool {
        return $inserting <= $current;
      };
    }
    
    public static function desc () {
      return function (int $inserting, int $current): bool {
        return $inserting > $current;
      };
    }




    public function __construct (OrderedListNode ...$nodes) {
      $this->setOrderFN(self::asc());

      foreach ($nodes as $node) {
        $this->add($node);
      }
    }

    public function add (OrderedListNode $insertNode) {
      $index = 0;
      foreach ($this->list as $currentNode) {
        if ($this->orderFN->__invoke($insertNode->orderBy, $currentNode->orderBy)) {
          array_splice($this->list, $index, 0, [$insertNode]);
          return;
        }

        $index++;
      }

      array_push($this->list, $insertNode);
    }

    static function parse (array $arr): OrderedList {
      $ol = new OrderedList();

      foreach ($arr as $oln) {
        $ol->add(new OrderedListNode($oln->orderBy, $oln->content));
      }

      return $ol;
    }



    // Iterator
    private $pointer = 0;
    public function rewind (): void {
      $this->pointer = 0;
    }
    public function current () {
      return $this->list[$this->pointer]->content;
    }
    public function key () {
      return $this->pointer;
    }
    public function next (): void {
      ++$this->pointer;
    }
    public function valid (): bool {
      return isset($this->list[$this->pointer]);
    }





    // JsonSerilize
    public function jsonSerialize() {
      $arr = [];
      foreach ($this->list as $child) {
        $arr[] = (method_exists($child, "jsonSerialize"))
          ? $child->jsonSerialize()
          : $child;
      }
      return $arr;
    }
  }