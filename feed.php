<?php
class feed{
	//Based on https://validator.w3.org/feed/docs/atom.html
	var $id;
	var $title;
	var $updated;
	var $author = null;
	var $link = null;
	var $items = array();
	function feed($id,$title){
		$this->id = $id;
		$this->title = $title;
		$this->updated = time();
		$this->link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";;
	}
	public static function textEscape($text){
		return str_replace("&","&amp;",iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $text));
	}
	function newItem($id,$title){
		$item = new class($id,$title){
			var $id;
			var $title; 
			var $updated;
			var $content = null;
			var $link = null;
			function __construct($id,$title){
				$this->id = $id;
				$this->title = $title;
				$this->updated = time();
			}
			function getItem(){
				$this->print = '<entry>';
				$this->print .= '<id>'.feed::textEscape($this->id).'</id>';
				$this->print .= '<title>'.feed::textEscape($this->title).'</title>';
				$this->print .= '<updated>'.feed::textEscape(date('Y-m-d\TH:i:s\Z',$this->updated)).'</updated>';
				if($this->content != null){
					$this->print .= '<content>'.feed::textEscape($this->content).'</content>';
				}
				if($this->link != null){
					$this->print .= '<link rel="alternate" href="'.$this->link.'" />';
				}
				$this->print .= '</entry>';
				return $this->print;
			}
		};
		$this->items[] = $item;
		return $item;
	}
	function getFeed(){
		$print = '<?xml version="1.0" encoding="utf-8"?><feed xmlns="http://www.w3.org/2005/Atom">';
		$print .= '<id>'.feed::textEscape($this->id).'</id>';
		$print .= '<title>'.feed::textEscape($this->title).'</title>';
		$print .= '<updated>'.feed::textEscape(date('Y-m-d\TH:i:s\Z',$this->updated)).'</updated>';
		if($this->author != null){
			$print .= '<author><name>'.feed::textEscape($this->author).'</name></author>';
		}
		if($this->link != null){
			$print .= '<link rel="self" href="'.feed::textEscape($this->link).'" />';
		}
		foreach($this->items as $item){
			$print .= $item->getItem();
		}
		$print .= '</feed>';
		return $print;
	}
	function printFeed($withHeader = true){
		if($withHeader){
			header('Content-Type: application/xml');
		}
		echo $this->getFeed();
	}
}
?>
