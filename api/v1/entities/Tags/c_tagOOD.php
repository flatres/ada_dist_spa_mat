<?php 

//  Categories --> TagMap --> Tag (attached to specific user)
// t_categories store a list of a users tag categories : 0 = vis to the whole school
// t_tags stores a list of a users tags : 0 = vis to whole school. A category of 0 incidcates uncategorized
// t_tagmap associates a users / school's (owner) tage with the user being tagged (target)

// If target = 0 then it is not applied to anyone

class Tag {

        
		public function __construct($target, $value, $category = NULL)
		{
			global $user_id; 
            
            $this->target = $target;
            $this->value = $this->normalize($value);
            $this->category = $this->normalize($category);
            $this->owner = $user_id;
            
            $this->reserved = false;
            $this->verify =0;
		}	
    
        //creates a reserved category and first tag. Returns tag ID
        public function createReserved($global=FALSE){
            
          $this->reserved = true;  
          return $this->create($global);
            
        }
    
        //a new tag. If global set at the school level by the administrator
        public function create($global=FALSE){
            
            global $user_id;
            
            $this->owner = $global == TRUE ? NULL : $user_id ; //school wide so no owner!
            
            //categories
            if($this->category == NULL){
                
                $catID = NULL;
                
            }else{
                
                //make category if it doesn't already exist
                $catID = $this->categoryExists();
                $catID = !$catID ? $this->newCategory() : $catID;
                
                $this->catID = $catID;
 
            }
            
            //tags
             //make tagMap if it doesn't already exist
            $tagID = $this->tagExists($catID);
            $tagID = !$tagID ? $this->insertTag($catID) : $tagID;    
            
            //actually apply the tag to the target            
            $tagMapID = $this->tagMapExists($tagID); 
            $tagMapID = !$tagMapID ? $this->newTagMap($tagID) : $tagMapID;    
            
            return $tagID;
        
            
        }
//TAGMAPS
    
     private function newTagMap($tagID){
            
            global $sql;
            
            if($this->target==0){return;}
            
            $bind = array($this->target, $tagID, $this->verify);
            return $sql->insert('t_tagmap', 'user_id, tag_id, verify', $bind);
            
        }
    
        private function tagMapExists($tagID){
            
            global $sql;
            
            
            $bind = array($this->target, $tagID);
            $d=$sql->select('t_tagmap', 'id', 'user_id = ? AND tag_id = ?', $bind);
            
            $result = isset($d[0]) ? $d[0]['id'] : FALSE;
            
            return $result;
            
            
        }
      
    
//TAGS  //returns id if so
    
        private function insertTag($catID){
            
            global $sql;
            global $user_school;
            
            $bind = array($catID, $user_school, $this->owner, $this->value);
            return $sql->insert('t_tags', 'category, school, user, name', $bind);
            
        }
    
    
        private function tagExists($catID){
            
            global $sql;
            global $user_school;
            
						if($catID==NULL){
							
								 $bind = array($this->value, $user_school);
            			         $d=$sql->select('t_tags', 'id, verify', 'name = ? AND school = ? AND isnull(category)', $bind);
            
						}else{
							
							$bind = array($this->value, $user_school, $catID);
            	            $d=$sql->select('t_tags', 'id, verify', 'name = ? AND school = ? AND category=?', $bind);
						}
					
            $result = isset($d[0]) ? $d[0]['id'] : FALSE;
            $this->verify = isset($d[0]) ? $d[0]['verify'] : 0;
            
            
            return $result;
            
            
        }
        
    
//CATEGORIES    
        private function newCategory(){
            
            global $sql;
            global $user_school;
					
            
            $bind = array($user_school, $this->owner, $this->category, $this->reserved);
            return $sql->insert('t_categories', 'school, user, name, reserved', $bind);
            
        }
    
        //makes string capital first letter of each word lower case everything else
        //So that tags don't become case sensitive
        private function normalize($string){
            
            return ucwords(strtolower($string));
					
            
        }
    
        private function categoryExists(){
            
            global $sql;
            global $user_school;
					
            if($this->owner==NULL){
							
							$bind = array($this->category, $user_school, $this->reserved);
              $d=$sql->select('t_categories', 'id', 'name = ? AND school = ? AND user is null AND reserved=?', $bind);
							
						}else{
						
							$bind = array($this->category, $user_school, $this->owner, $this->reserved);
              $d=$sql->select('t_categories', 'id', 'name = ? AND school = ? AND user=? AND reserved=?', $bind);
            
							
						}
					
            $result = isset($d[0]) ? $d[0]['id'] : NULL;
            
            return $result;
            
            
        }
	  
}