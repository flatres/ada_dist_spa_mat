<?php

//  Categories --> TagMap --> Tag (attached to specific user)
// t_categories store a list of a users tag categories : 0 = vis to the whole school
// t_tags stores a list of a users tags : 0 = vis to whole school. A category of 0 incidcates uncategorized
// t_tagmap associates a users / school's (owner) tage with the user being tagged (target)

class Tags {


		public function __construct()
		{

		}


				//returns a category / value array of the user's tags and a searchable string of all tags
				public function readUserTags($userid){

					global $sql;
					global $user_school;
					global $user_id;

					$tagmap = $sql->select('t_tagmap', 'tag_id', 'user_id=?', array($userid));

					$tagString = ''; //used for Regexp Searching
					$return_array = array();
					$tags = array();

					foreach($tagmap as $tag){

						$tagid = $tag['tag_id'];

						//get value and category
						$tagData = $sql->select('t_tags', 'category, name', 'id=? AND school = ? AND (user = ? or isnull(user))', array($tagid, $user_school, $user_id));

						$tagData = isset($tagData[0]) ? $tagData[0] : null;

						$catID = $tagData['category'];
						$tagName = $tagData['name'];

						//get category name if there is one
						if($catID != null){

							$catData = $sql->select('t_categories', 'name', 'id=?', array($catID));

							$catName = isset($catData[0]) ? $catData[0]['name'] : '';

						}else{

							$catName = '';

						}

						$tagString .= ' ' . $catName . ' ' .  $tagName;

						$tag = array();
						$tag['category'] = $catName;
						$tag['value'] = $tagName;

						$tags[] = $tag;

					}

					$return_array['tags'] = $tags;
					$return_array['tagString'] = $tagString;

					return $return_array;

				}


	 			//returns an array of the school categories and associated tags to be used for filtering
				public function getFilters($b_includeMembers = false){

					global $user_school;
					global $sql;

					$this->b_includeMembers = $b_includeMembers;



					$filters = array();

					$cats = $sql->select('t_categories', 'id, name', 'school=? AND user IS NULL AND cumulative=0 ORDER BY name ASC', array($user_school));

					foreach($cats as $cat){

						$locked = false;
						$filter = array();
            $filter['id']=$cat['id'];
						$filter['name']=$cat['name'];
						$filter['tags']=$this->getCatTags($cat['id'], true, $locked); //locked if it contains a wonde tag
						$filter['locked'] = $locked;
						$filters[]=$filter;

					}

					return $filters;


				}

		//retunrs an array of a categories tag names and optionally now many users from the school have each
		public function getCatTags($catID, $count = NULL, &$locked = false){

			global $sql;
			global $user_school;

			$locked = false;

			$tags = $sql->select('t_tags', 'id, wonde_id, name', 'category =? ORDER BY name ASC', array($catID));

			// echo $this->b_includeMembers . 'p';
			if(is_null($count)){

				if($this->b_includeMembers==true){

					foreach($tags as &$tag){

						$tag['members'] = $this->getMembers($tag['id']);

					}

				}

				return $tags;

			}else{

				foreach($tags as &$tag){

					$tag['locked'] = is_null($tag['wonde_id']) ? false : true;

					if(!is_null($tag['wonde_id'])) $locked = true; //so that cetegory is locked

					$tag['count'] = $this->countTags($tag['id']);

					if($this->b_includeMembers==true) $tag['members'] = $this->getMembers($tag['id']);

				}

				return $tags;

			}

		}

		private function getMembers($tagID){

			global $sql;

			$d =  $sql->query('SELECT id, user_id, tag_id from t_tagmap WHERE tag_id = ?', array($tagID));

			return $d;

		}

		//counts the number of times this tag has been applied in the school
		private function countTags($tagID, $user = NULL){

			global $sql;
			global $user_school;

			if(is_null($user)){

					$tags = $sql->select('t_tagmap', 'id', 'tag_id=?', array($tagID));

			}else{

					$tags = $sql->select('t_tagmap', 'id', 'tag_id=? AND user_id=?', array($tagID, $user));

			}

			return $sql->rowCount();



		}

}
