<?php
	
	class User {
		
		private $dbconn ;
		
		public function __construct($dbconnect) {
			$this->dbconn = $dbconnect ;
		}
		
		public function user_register($username, $email, $password) {
			$create_time = date('Y-m-d H:i:s') ;
			$passHash = password_hash($password, PASSWORD_DEFAULT) ;
			$user_role = 1;
			
			try {
				$statement = $this->dbconn->prepare('INSERT INTO users(user_role, username, email, password, create_by, create_time) VALUES(:user_role, :username, :email, :pass, :createBy, :create_time)') ;
				
				$statement->bindparam(':user_role', $user_role) ;
				$statement->bindparam(':username', $username) ;
				$statement->bindparam(':email', $email) ;
				$statement->bindparam(':pass', $passHash) ;
				$statement->bindparam(':createBy', $username);
				$statement->bindparam(':create_time', $create_time) ;
				
				$statement->execute() ;
				
				return $statement ;
				
			} catch(PDOException $e) {
					echo $e->getMessage() ;
			}
			
		}

		public function checkexistsEmail() {
			try {
				$statement = $this->dbconn->prepare('SELECT username,email FROM users');
				$statement->execute();
				$checkExists = $statement->fetchAll(PDO::FETCH_ASSOC);
				return $checkExists;

			} catch(PDOException $e) {
				return $e->getMessage();
			}
		}

		public function user_login($email, $password) {
			try {
				$statement = $this->dbconn->prepare('SELECT * FROM users WHERE email = :email LIMIT 1') ;
				$statement->bindparam(':email', $email) ;
				$statement->execute() ;
				$userRow = $statement->fetch(PDO::FETCH_ASSOC) ;
				
				if($statement->rowCount() > 0 ) {
					if(password_verify($password, $userRow['password'])) {
						$_SESSION['user_session'] = $userRow['id'] ;
						return true ;
					} else {
						return false ;
					}
				}
			} catch(PDOException $e) {
				return false ;
			}
		}



		public function updateRemember($identifier, $token, $uid) {
			if(!empty($identifier) && !empty($token)) {
				try {
					$statement = $this->dbconn->prepare('UPDATE users SET remember_identifier =:identifier, remember_token =:token WHERE id =:uid LIMIT 1');
					$statement->bindparam(':identifier', $identifier);
					$statement->bindparam(':token', $token);
					$statement->bindparam(':uid', $uid);

					if($statement->execute()) {
						return true;
					} else {
						return false;
					}

				} catch (PDOException $e) {
					return false;
				}
			} else {
				return false;
			}
		}

		public function checkRemember($identifier, $token) {
			try {
				$statement = $this->dbconn->prepare('SELECT * FROM users WHERE remember_identifier =:identifier AND remember_token =:token');
				$statement->bindparam(':identifier', $identifier);
				$statement->bindparam(':token', $token);
				$statement->execute();
				$data = $statement->fetch(PDO::FETCH_ASSOC);
				if(empty($data)) {
					return false;
				} else {
					return $data;
				}

			} catch(PDOException $e) {
				return $e->getMessage();
			}
		}

		public function removeRemember($uid) {
			try {
				$statement = $this->dbconn->prepare('UPDATE users SET remember_identifier = "", remember_token = "" WHERE id =:uid LIMIT 1');
				$statement->bindparam(':uid', $uid);

				if($statement->execute()) {
					return true;
				} else {
					return false;
				}

			} catch (PDOException $e) {
				return false;
			}
		}
		

		
		public function is_loggedin() {
			if(isset($_SESSION['user_session'])) {
				return true ;
			}
		}
		
		
		
		public function redirect($url) {
			header ("Location: $url") ;
			exit ;
		}
		
		
    public function logout() {
      session_destroy();
      unset($_SESSION['user_session']); 
      return true;
    }
		public function finduserdata($id) {
			if(!empty($id)) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM users WHERE id = :id LIMIT 1') ;
					$statement->bindparam(':id', $id) ;
					$statement->execute() ;
					$userRow = $statement->fetch(PDO::FETCH_ASSOC) ;
					return $userRow ;
					
				} catch(PDOException $e) {
					return $e->getMessage() ;
				}
			}
		}
		

		public function user_setting($id, $email, $username, $full_name, $phone_num, $address) {
			if(!empty($id) && !empty($email)) {
			$update_time = date('Y-m-d H:i:s');
			
				try {
					$statement = $this->dbconn->prepare('UPDATE users SET username =:username, full_name =:full_name, phone_num =:phone_num, address =:address, update_by =:updateBy, update_time =:update_time  WHERE id =:id AND email =:email');
					
					$statement->bindparam(':id', $id);
					$statement->bindparam(':email', $email);
					$statement->bindparam(':username', $username);
					$statement->bindparam(':full_name', $full_name);
					$statement->bindparam(':phone_num', $phone_num);
					$statement->bindparam(':address', $address);
					// $statement->bindparam(':imgPath', $imgPath);
					// $statement->bindparam(':imgName', $imgName);
					$statement->bindparam(':updateBy', $username);
					$statement->bindparam(':update_time', $update_time);
					
					if($statement->execute()) {
						return true;
					} else {
						return false;
					}
					
					
				} catch(PDOException $e) {
					return false;
				}
			}
		}

		public function userProfileimg($uid, $email, $imgPath, $imgName) {
			if(!empty($uid) && !empty($email)) {
				try {
					$statement = $this->dbconn->prepare('UPDATE users SET img_path =:imgPath, img_name =:imgName WHERE id =:uid AND email =:email LIMIT 1');
					$statement->bindparam(':uid', $uid);
					$statement->bindparam(':email', $email);
					$statement->bindparam(':imgPath', $imgPath);
					$statement->bindparam(':imgName', $imgName);

					if($statement->execute()) {
						return true;
					} else {
						return false;
					}

				} catch(PDOException $e) {
					return false;
				}
			}
		}

		public function checkCurrpass($uid, $password) {
			if(!empty($uid) && !empty($password)) {
				try {
					$statement = $this->dbconn->prepare('SELECT password FROM users WHERE id =:uid LIMIT 1');
					$statement->execute(array(":uid" => $uid));
					$userPass = $statement->fetch(PDO::FETCH_ASSOC);

					if(password_verify($password,$userPass['password'])) {
						return true;
					} else {
						return false;
					}

				} catch(PDOException $e) {
					return false;
				}
			}
		}



		public function changePass($uid, $updateBy, $newPass) {
			if(!empty($uid) && !empty($newPass)) {
				$updateTime = date("Y-m-d H:i:s");
				$passHash = password_hash($newPass, PASSWORD_DEFAULT) ;
				try {
					$statement = $this->dbconn->prepare('UPDATE users SET password =:newPass, update_by =:updateBy, update_time =:updateTime WHERE id =:uid LIMIT 1 ');

					$statement->bindparam(":uid", $uid);
					$statement->bindparam(":newPass", $passHash);
					$statement->bindparam(":updateBy", $updateBy);
					$statement->bindparam(":updateTime", $updateTime);

					if($statement->execute()) {
						return true;
					} else {
						return false;
					}

				} catch(PDOException $e) {
					return false;
				}
			}
		}

		public function seller_register($user_id, $shop, $email, $phone_num, $address, $createBy) {
			$create_time = date('Y-m-d H:i:s') ;
			
			try {
				$statement = $this->dbconn->prepare('INSERT INTO sellers(user_id, shop_name, email, phone_num, address, create_by, create_time) VALUES(:user_id, :shopName, :email, :phone_num, :address, :createBy, :create_time)') ;
				
				$statement->bindparam(':user_id', $user_id) ;
				$statement->bindparam(':shopName', $shop) ;
				$statement->bindparam(':email', $email) ;
				$statement->bindparam(':phone_num', $phone_num) ;
				$statement->bindparam(':address', $address) ;
				$statement->bindparam(':createBy', $createBy) ;
				$statement->bindparam(':create_time', $create_time) ;
				
				$statement->execute() ;
				
				return $statement ;
				
			} catch(PDOException $e) {
					echo $e->getMessage() ;
			}
			
		}

		public function seller_setting($sid, $semail, $shopName, $phoneNum, $address, $bgPath, $bgName, $updateBy) {
			if(!empty($sid) && !empty($semail)) {
			$update_time = date('Y-m-d H:i:s');
			
				try {
					$statement = $this->dbconn->prepare('UPDATE sellers SET shop_name =:shopName, phone_num =:phoneNum, address =:address,
					bg_path =:bgPath, bg_name =:bgName, update_by =:updateBy, update_time =:update_time  WHERE id =:sid AND email =:semail LIMIT 1');
					
					$statement->bindparam(':sid', $sid);
					$statement->bindparam(':semail', $semail);
					$statement->bindparam(':shopName', $shopName);
					$statement->bindparam(':phoneNum', $phoneNum);
					$statement->bindparam(':address', $address);
					$statement->bindparam(':bgPath', $bgPath);
					$statement->bindparam(':bgName', $bgName);
					$statement->bindparam(':updateBy', $updateBy);
					$statement->bindparam(':update_time', $update_time);
					
					if($statement->execute()) {
						return true;
					} else {
						return false;
					}
					
					
				} catch(PDOException $e) {
					return false;
				}
			}
		}
		public function user_role($id, $email) {
			if(!empty($id) && !empty($email)) {
			$update_time = date('Y-m-d H:i:s');
			$user_role = 2;
			
				try {
					$statement = $this->dbconn->prepare('UPDATE users SET user_role =:user_role WHERE id =:id AND email =:email LIMIT 1');
					
					$statement->bindparam(':id', $id);
					$statement->bindparam(':email', $email);
					$statement->bindparam(':user_role', $user_role);
					
					if($statement->execute()) {
						return true;
					} else {
						return false;
					}
					
					
				} catch(PDOException $e) {
					return false;
				}
			}
		}
	
		public function findsellerid($userId) {
			if(!empty($userId)) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM sellers WHERE user_id = :userId LIMIT 1');
					$statement->bindparam(':userId', $userId);
					$statement->execute();
					$sellerRow = $statement->fetch(PDO::FETCH_ASSOC);
					return $sellerRow;
					
				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function seller_uinfo($sellerUid) {
				try {
					$statement = $this->dbconn->prepare('SELECT username, email, img_path, img_name FROM users WHERE id = :sellerUid LIMIT 1');
					$statement->bindparam(':sellerUid', $sellerUid);
					$statement->execute();
					$sellerRow = $statement->fetch(PDO::FETCH_ASSOC);
					return $sellerRow;
					
				} catch(PDOException $e) {
					return $e->getMessage();
				}
		}

		public function sellerIntro($createBy) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM sellers WHERE id = :createBy LIMIT 1');
					$statement->bindparam(':createBy', $createBy);
					$statement->execute();
					$sellerRow = $statement->fetch(PDO::FETCH_ASSOC);
					return $sellerRow;
					
				} catch(PDOException $e) {
					return $e->getMessage();
				}
		}

		public function sellerintroProduct($sid) {
			if(!empty($sid)) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM products WHERE create_by =:sid AND status = "public" ORDER BY id DESC');
					$statement->bindparam(':sid', $sid);
					$statement->execute();
					$product = $statement->fetchAll(PDO::FETCH_ASSOC);
					return $product;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}


		public function getProductonce($pid) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM products WHERE id = :pid LIMIT 1');
					$statement->bindparam(':pid', $pid);
					$statement->execute();
					$sellerRow = $statement->fetch(PDO::FETCH_ASSOC);
					return $sellerRow;
					
				} catch(PDOException $e) {
					return $e->getMessage();
				}
		}

		public function addtoCart($pid, $sid, $pname, $imgPath, $imgName, $quantity, $price, $addBy) {
			if(!empty($pid) && !empty($addBy)) {
				$addTime = date('Y-m-d H:i:s');
				try {
					$statement = $this->dbconn->prepare('INSERT INTO cart(product_name, img_path, img_name, quantity, price, pid, sid, add_by, add_time) VALUES(:pname, :imgPath, :imgName, :quantity, :price, :pid, :sid, :addBy, :addTime)');

					$statement->bindparam(':pname', $pname);
					$statement->bindparam(':imgPath', $imgPath);
					$statement->bindparam(':imgName', $imgName);
					$statement->bindparam(':quantity', $quantity);
					$statement->bindparam(':price', $price);
					$statement->bindparam(':pid', $pid);
					$statement->bindparam(':sid', $sid);
					$statement->bindparam(':addBy', $addBy);
					$statement->bindparam(':addTime', $addTime);

					$statement->execute();

					return $statement;


				} catch(PDOException $e) {
					return $e->getMessage();
				} 
			}
		}

		public function getCartdata($uid) {
			if(!empty($uid)) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM cart WHERE add_by =:uid AND status != "paid" ORDER BY id ASC');
					$statement->bindparam(':uid', $uid);
					$statement->execute();
					$cartdata = $statement->fetchAll(PDO::FETCH_ASSOC);
					return $cartdata;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function cartPending($cid, $uid) {
			if(!empty($uid)) {
				$status = 'pending';
				$updatetime = date('Y-m-d H:i:s');
				try {
					$statement = $this->dbconn->prepare('UPDATE cart SET status =:status, update_by =:updateby, update_time =:updatetime WHERE  add_by =:uid AND id =:cid ');
					$statement->bindparam(':uid', $uid);
					$statement->bindparam(':status', $status);
					$statement->bindparam(':cid', $cid);
					$statement->bindparam(':updateby', $uid);
					$statement->bindparam(':updatetime', $updatetime);

					if($statement->execute()) {
						return true;
					} else {
						return false;
					}

				} catch(PDOException $e) {
					return false;
				}
			}
		}

		public function cartCheckout($cid, $uid) {
			if(!empty($uid)) {
				$status = 'checkout';
				$updatetime = date('Y-m-d H:i:s');
				try {
					$statement = $this->dbconn->prepare('UPDATE cart SET status =:status, update_by =:updateby, update_time =:updatetime WHERE  add_by =:uid AND id =:cid ');
					$statement->bindparam(':uid', $uid);
					$statement->bindparam(':status', $status);
					$statement->bindparam(':cid', $cid);
					$statement->bindparam(':updateby', $uid);
					$statement->bindparam(':updatetime', $updatetime);

					if($statement->execute()) {
						return true;
					} else {
						return false;
					}

				} catch(PDOException $e) {
					return false;
				}
			}
		}

		public function minusProduct($pid, $qty) {
			if(!empty($pid)) {
				try {
					$statement = $this->dbconn->prepare('UPDATE products SET product_quantity =(product_quantity - :qty) WHERE id =:pid LIMIT 1');
					$statement->bindparam(':pid', $pid);
					$statement->bindparam(':qty', $qty);

					if($statement->execute()) {
						return true;
					} else {
						return false;
					}

				} catch(PDOException $e) {
					return false;
				}
			}
		}


		public function deleteCart($cid, $uid) {
			if(!empty($cid) && !empty($uid)) {
				try {
					$statement = $this->dbconn->prepare('DELETE FROM cart WHERE id =:cid AND add_by =:uid LIMIT 1');
					$statement->bindparam(':cid', $cid);
					$statement->bindparam(':uid', $uid);
					$statement->execute();
					return true;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}
		public function sumSubtotal($uid) {
			if(!empty($uid)) {
				try {
					$statement = $this->dbconn->prepare('SELECT SUM(quantity*price) AS subtotal FROM cart WHERE add_by =:uid AND status = "checkout"');
					$statement->bindparam(':uid', $uid);
					$statement->execute();
					$subtotal = $statement->fetch(PDO::FETCH_ASSOC);
					return $subtotal;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function addwishlist($pname, $ppath, $pimg, $price, $pid, $uid) {
			if(!empty($uid)) {
				$addtime = date('Y-m-d H:i:s');
				try {
					$statement = $this->dbconn->prepare('INSERT INTO wishlist(product_name, product_path, product_img, price, pid, add_by, add_time) VALUES(:pname, :ppath, :pimg, :price, :pid, :addby, :addtime)');
					$statement->bindparam(':pname', $pname);
					$statement->bindparam(':ppath', $ppath);
					$statement->bindparam(':pimg', $pimg);
					$statement->bindparam(':price', $price);
					$statement->bindparam(':pid', $pid);
					$statement->bindparam(':addby', $uid);
					$statement->bindparam(':addtime', $addtime);

					$statement->execute();
					return $statement;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}


		public function getwishlistStatus($pid,$uid) {
			if(!empty($pid) && !empty($uid)) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM wishlist_action WHERE product_id =:pid AND create_by =:uid ORDER BY id DESC LIMIT 1');
					$statement->bindparam(':pid', $pid);
					$statement->bindparam(':uid', $uid);
					$statement->execute();
					$status = $statement->fetch(PDO::FETCH_ASSOC);
					return $status;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function likeWishlist($pid, $uid) {
			if(!empty($pid) && !empty($uid)) {
				$action = 'like';
				$create_time = date('Y-m-d H:i:s');
				try {
					$statement = $this->dbconn->prepare('INSERT INTO wishlist_action(product_id, action, create_by, create_time) VALUES(:pid, :action, :create_by, :create_time)');
					$statement->bindparam(':pid', $pid);
					$statement->bindparam(':action', $action);
					$statement->bindparam(':create_by', $uid);
					$statement->bindparam(':create_time', $create_time);

					$statement->execute();
					return $statement;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function unlikeWishlist($pid, $uid) {
			if(!empty($pid) && !empty($uid)) {
				$action = 'unlike';
				$create_time = date('Y-m-d H:i:s');
				try {
					$statement = $this->dbconn->prepare('INSERT INTO wishlist_action(product_id, action, create_by, create_time) VALUES(:pid, :action, :create_by, :create_time)');
					$statement->bindparam(':pid', $pid);
					$statement->bindparam(':action', $action);
					$statement->bindparam(':create_by', $uid);
					$statement->bindparam(':create_time', $create_time);

					$statement->execute();
					return $statement;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}
		public function deleteWishlist($pid, $uid) {
			if(!empty($pid) && !empty($uid)) {
				try {
					$statement = $this->dbconn->prepare('DELETE FROM wishlist WHERE pid =:pid AND add_by =:uid LIMIT 1');
					$statement->bindparam(':pid', $pid);
					$statement->bindparam(':uid', $uid);
					$statement->execute();
					return true;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function displayWishlist($uid) {
			if(!empty($uid)) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM wishlist WHERE add_by =:uid ORDER BY id DESC');
					$statement->bindparam(':uid', $uid);
					$statement->execute();
					$wishlist_data = $statement->fetchAll(PDO::FETCH_ASSOC);
					return $wishlist_data;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		} 
		public function addOrder($purchaseQty, $price, $userAddress, $orderBy) {
			if(!empty($orderBy)) {
				$orderTime = date('Y-m-d H:i:s');
				$orderId = MD5($orderBy.$orderTime);

				try {
					$statement = $this->dbconn->prepare('INSERT INTO orders(order_id, purchase_qty, price, address, order_by, order_time) VALUES(:orderId, :purchaseQty, :price, :userAddress, :orderBy, :orderTime)');

					$statement->bindparam(':orderId', $orderId);
					$statement->bindparam(':purchaseQty', $purchaseQty);
					$statement->bindparam(':price', $price);
					$statement->bindparam(':userAddress', $userAddress);
					$statement->bindparam(':orderBy', $orderBy);
					$statement->bindparam(':orderTime', $orderTime);

					$statement->execute();
					return $statement;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function updateOrderid($orderid, $uid) {
			if(!empty($uid)) {
				$status = 'paid';
				$updatetime = date('Y-m-d H:i:s');
				try {
					$statement = $this->dbconn->prepare('UPDATE cart SET status =:status, order_id =:orderid, update_by =:updateby, update_time =:updatetime WHERE   add_by =:uid AND status ="checkout" ');
					$statement->bindparam(':uid', $uid);
					$statement->bindparam(':status', $status);
					$statement->bindparam(':orderid', $orderid);
					$statement->bindparam(':updateby', $uid);
					$statement->bindparam(':updatetime', $updatetime);

					if($statement->execute()) {
						return true;
					} else {
						return false;
					}

				} catch(PDOException $e) {
					return false;
				}
			}
		}
		public function getorderid($uid) {
			if(!empty($uid)) {
				try {
					$statement = $this->dbconn->prepare('SELECT order_id FROM orders WHERE order_by =:uid ORDER BY id DESC ');
					$statement->bindparam(':uid', $uid);
					$statement->execute();
					$orderData = $statement->fetch(PDO::FETCH_ASSOC);
					return $orderData;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function thankyouData($orderid) {
			if(!empty($orderid)) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM orders WHERE order_id = :orderid ORDER BY id DESC LIMIT 1');
					$statement->bindparam(':orderid', $orderid);
					$statement->execute();
					$thankyou_data = $statement->fetch(PDO::FETCH_ASSOC);
					return $thankyou_data;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function getorderdata($uid) {
			if(!empty($uid)) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM orders WHERE order_by =:uid ORDER BY id DESC ');
					$statement->bindparam(':uid', $uid);
					$statement->execute();
					$orderData = $statement->fetchAll(PDO::FETCH_ASSOC);
					return $orderData;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function gethistorydata($uid, $orderid) {
			if(!empty($uid) && !empty($orderid)) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM cart WHERE add_by =:uid AND order_id =:orderid ORDER BY id ASC');
					$statement->bindparam(':uid', $uid);
					$statement->bindparam(':orderid', $orderid);

					$statement->execute();
					$data = $statement->fetchAll(PDO::FETCH_ASSOC);
					return $data;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function sumhistoryPrice($orderid, $uid) {
			if(!empty($orderid) && !empty($uid)) {
				try {
					$statement = $this->dbconn->prepare('SELECT SUM(quantity*price) AS subtotal FROM cart WHERE add_by =:uid AND order_id =:orderid');
					$statement->bindparam(':uid', $uid);
					$statement->bindparam(':orderid', $orderid);
					$statement->execute();
					$subtotal = $statement->fetch(PDO::FETCH_ASSOC);
					return $subtotal;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function addProduct($sid, $imgPath, $imgName, $pname, $pprice, $pquantity, $pcat) {
			if(!empty($sid)) {
				$createTime = date('Y-m-d H:i:s');
				$productStatus = 'public';

				try {
					$statement = $this->dbconn->prepare('INSERT INTO products(img_path, img_name, product_name, product_price, product_quantity, category_type, status, create_by, create_time) VALUES(:imgPath, :imgName, :pname, :pprice, :pquantity, :pcat, :status, :createBy, :createTime)');

					$statement->bindparam(':imgPath', $imgPath);
					$statement->bindparam(':imgName', $imgName);
					$statement->bindparam(':pname', $pname);
					$statement->bindparam(':pprice', $pprice);
					$statement->bindparam(':pquantity', $pquantity);
					$statement->bindparam(':pcat', $pcat);
					$statement->bindparam(':status', $productStatus);
					$statement->bindparam(':createBy', $sid);
					$statement->bindparam(':createTime', $createTime);

					$statement->execute();

					return $statement;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			} 
		}

		public function deleteproduct($pid, $sid) {
			if(!empty($pid) && !empty($sid)) {
				$status = 'hide';
				try {
					$statement = $this->dbconn->prepare('UPDATE products SET status =:status WHERE id =:pid AND create_by =:sid LIMIT 1');
					$statement->bindparam(':status', $status);
					$statement->bindparam(':pid', $pid);
					$statement->bindparam(':sid', $sid);

					if($statement->execute()) {
						return true;
					} else {
						return false;
					}

				} catch(PDOException $e) {
					return false;
				}
			}
		}
		public function selectproductId($sid) {
			if(!empty($sid)) {
				try {
					$statement = $this->dbconn->prepare('SELECT id FROM products WHERE create_by = :sid ORDER BY id DESC LIMIT 1');
					$statement->bindparam(':sid', $sid);
					$statement->execute();
					$selectpid = $statement->fetch(PDO::FETCH_ASSOC);
					return $selectpid;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function insertpDetail($pid, $imgPath, $imgName, $sid) {
			if(!empty($sid)) {
				$createTime = date('Y-m-d H:i:s');

				try {
					$statement = $this->dbconn->prepare('INSERT INTO product_gallery(img_path, img_name, product_id, create_by, create_time) VALUES (:imgPath, :imgName, :pid, :createBy, :createTime)');

					$statement->bindparam(':imgPath', $imgPath);
					$statement->bindparam(':imgName', $imgName);
					$statement->bindparam(':pid', $pid);
					$statement->bindparam(':createBy', $sid);
					$statement->bindparam(':createTime', $createTime);

					$statement->execute();

					return $statement;

				} catch(PDOException $e) {
					echo $e->getMessage();
				}
			}
		}
		public function displayProductcover() {
			try {
				$statement = $this->dbconn->prepare('SELECT id, product_name, img_path, img_name, product_quantity, product_price, create_by FROM products WHERE status = "public" ORDER BY id DESC LIMIT 20');
				$statement->execute();
				$productDis = $statement->fetchAll(PDO::FETCH_ASSOC);
				return $productDis;
			} catch(PDOException $e) {
				return $e->getMessage();
			}
		}

		public function displayAllProduct() {
			try {
				$statement = $this->dbconn->prepare('SELECT id, product_name, img_path, img_name, product_price, create_by FROM products WHERE status = "public" ORDER BY id ASC');
				$statement->execute();
				$productDis = $statement->fetchAll(PDO::FETCH_ASSOC);
				return $productDis;
			} catch(PDOException $e) {
				return $e->getMessage();
			}
		}

		public function productDetail($pid) {
			try {
				$statement = $this->dbconn->prepare('SELECT * FROM products WHERE id = :pid LIMIT 1');
				$statement->bindparam(':pid', $pid);
				$statement->execute();
				$productDetail = $statement->fetch(PDO::FETCH_ASSOC);
				return $productDetail;
			} catch(PDOException $e) {
				return $e->getMessage();
			}
		}

		public function productImage($pid) {
			if(!empty($pid)) {
				try{
					$statement = $this->dbconn->prepare('SELECT id, img_path, img_name, product_id, create_by FROM product_gallery WHERE product_id = :pid ORDER BY id ASC');

					$statement->bindparam(':pid', $pid);

					$statement->execute();

					$productImage = $statement->fetchAll(PDO::FETCH_ASSOC);
					return $productImage;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function addedProduct($sid) {
			if(!empty($sid)) {
				try {
				$statement = $this->dbconn->prepare('SELECT * FROM products WHERE create_by = :sid AND status = "public" ORDER BY id DESC');

				$statement->bindparam(':sid', $sid);

				$statement->execute();
				$getProduct = $statement->fetchAll(PDO::FETCH_ASSOC);
				return $getProduct;

			} catch(PDOException $e) {
				return $e->getMessage();
			}
			}
		}

		public function sellergetdata($sid) {
			if(!empty($sid)) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM cart WHERE sid =:sid AND status = "paid" ORDER BY add_by, id ASC');
					$statement->bindparam(':sid', $sid);
					$statement->execute();
					$getdata = $statement->fetchAll(PDO::FETCH_ASSOC);
					return $getdata;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function getordertime($orderid) {
			if(!empty($orderid)) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM orders WHERE order_id =:orderid ORDER BY id ');
					$statement->bindparam(':orderid', $orderid);
					$statement->execute();
					$orderData = $statement->fetchAll(PDO::FETCH_ASSOC);
					return $orderData;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}




		
		
	} 








	class Admin {
		
		private $dbconn ;
		
		public function __construct($dbconnect) {
			$this->dbconn = $dbconnect ;
		}
	
		public function findadminid($user_id) {
			if(!empty($user_id)) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM admin WHERE user_id = :user_id LIMIT 1');

					$statement->bindparam(':user_id', $user_id);
					$statement->execute();
					$adminData = $statement->fetch(PDO::FETCH_ASSOC);
					return $adminData;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}


		public function displayAlluser() {
			try {
				$statement = $this->dbconn->prepare('SELECT * FROM users ORDER BY ID ASC');
				$statement->execute();
				$userData = $statement->fetchAll(PDO::FETCH_ASSOC);
				return $userData;

			} catch(PDOException $e) {
				return $e->getMessage();
			}
		}


		public function findslide() {
				try {
					$statement = $this->dbconn->prepare('SELECT id, img_path, img_name FROM admin_panel');
					$statement->execute();
					$slide = $statement->fetchAll(PDO::FETCH_ASSOC);
					return $slide;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
		}

		public function fileChecking($directory, $fileName, $fileSize, $fileType, $fileTmp) {
			if($fileSize < 1966080) {
			  if($fileType == "image/jpg" || $fileType == "image/jpeg" || $fileType == "image/png" || $fileType == "image/gif") {

			     	move_uploaded_file($fileTmp, $directory . $fileName);
			       return 3;
			  } else {
			  	return 2;
			    
			  }
			} else {
				return 1;
			  
			}
		}

		public function uploadSlide1($user_role, $imgPath, $imgName, $adminName) {
			if($user_role == 3) {
				$update_time = date('Y-m-d H:i:s');
				try {

					$statement = $this->dbconn->prepare('UPDATE admin_panel SET img_path=:imgPath, img_name=:imgName, update_by=:update_by, update_time=:update_time WHERE id = 1') ;

					$statement->bindparam(':imgPath', $imgPath);
					$statement->bindparam(':imgName', $imgName);
					$statement->bindparam(':update_by', $adminName);
					$statement->bindparam(':update_time', $update_time);

					if($statement->execute()) {
						return true;
					} else {
						return false;
					}

				} catch(PDOException $e) {
					return false;
				}
			}
		}


		public function uploadSlide2($user_role, $imgPath, $imgName, $adminName) {
			if($user_role == 3) {
				$update_time = date('Y-m-d H:i:s');
				try {

					$statement = $this->dbconn->prepare('UPDATE admin_panel SET img_path=:imgPath, img_name=:imgName, update_by=:update_by, update_time=:update_time WHERE id = 2') ;

					$statement->bindparam(':imgPath', $imgPath);
					$statement->bindparam(':imgName', $imgName);
					$statement->bindparam(':update_by', $adminName);
					$statement->bindparam(':update_time', $update_time);

					if($statement->execute()) {
						return true;
					} else {
						return false;
					}

				} catch(PDOException $e) {
					return false;
				}
			}
		}


		public function uploadSlide3($user_role, $imgPath, $imgName, $adminName) {
			if($user_role == 3) {
				$update_time = date('Y-m-d H:i:s');
				try {

					$statement = $this->dbconn->prepare('UPDATE admin_panel SET img_path=:imgPath, img_name=:imgName, update_by=:update_by, update_time=:update_time WHERE id = 3') ;

					$statement->bindparam(':imgPath', $imgPath);
					$statement->bindparam(':imgName', $imgName);
					$statement->bindparam(':update_by', $adminName);
					$statement->bindparam(':update_time', $update_time);

					if($statement->execute()) {
						return true;
					} else {
						return false;
					}

				} catch(PDOException $e) {
					return false;
				}
			}
		}


		public function uploadSlide4($user_role, $imgPath, $imgName, $adminName) {
			if($user_role == 3) {
				$update_time = date('Y-m-d H:i:s');
				try {

					$statement = $this->dbconn->prepare('UPDATE admin_panel SET img_path=:imgPath, img_name=:imgName, update_by=:update_by, update_time=:update_time WHERE id = 4') ;

					$statement->bindparam(':imgPath', $imgPath);
					$statement->bindparam(':imgName', $imgName);
					$statement->bindparam(':update_by', $adminName);
					$statement->bindparam(':update_time', $update_time);

					if($statement->execute()) {
						return true;
					} else {
						return false;
					}

				} catch(PDOException $e) {
					return false;
				}
			}
		}


		//	DISPLAY CATOGORIES
		public function findCat() {
				try {
					$statement = $this->dbconn->prepare('SELECT id, category_path, category_file, category_name FROM category ORDER BY id ASC');
					$statement->execute();
					$slide = $statement->fetchAll(PDO::FETCH_ASSOC);
					return $slide;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
		}



		//	FILTER CATOGORIES
		public function getCategoryid($catid) {
			if(!empty($catid)) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM category WHERE id =:catid LIMIT 1');
					$statement->bindparam(':catid', $catid);
					$statement->execute();
					$categoryFilter = $statement->fetch(PDO::FETCH_ASSOC);
					return $categoryFilter;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}


		public function filterCategory($catName) {
			if(!empty($catName)) {
				try {
					$statement = $this->dbconn->prepare('SELECT * FROM products WHERE category_type =:catName AND status = "public" ORDER BY id DESC');
					$statement->bindparam(':catName', $catName);
					$statement->execute();
					$categoryFilter = $statement->fetchAll(PDO::FETCH_ASSOC);
					return $categoryFilter;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function createCat($userRole, $catName, $catPath, $catFile, $createBy) {
			if($userRole == 3 ) {
				$createTime = date('Y-m-d H:i:s');
				try {
					$statement = $this->dbconn->prepare('INSERT INTO category(category_name, category_path, category_file, create_by, create_time) VALUES(:catName, :catPath, :catFile, :createBy, :createTime)');

					$statement->bindparam(':catName', $catName);
					$statement->bindparam(':catPath', $catPath);
					$statement->bindparam(':catFile', $catFile);
					$statement->bindparam(':createBy', $createBy);
					$statement->bindparam(':createTime', $createTime);

					$statement->execute();

					return $statement;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}

		public function deleteCategory($catid) {
			if(!empty($catid)) {
				try {
					$statement = $this->dbconn->prepare('DELETE FROM category WHERE id =:catid LIMIT 1');
					$statement->bindparam(':catid', $catid);
					$statement->execute();
					return true;

				} catch(PDOException $e) {
					return $e->getMessage();
				}
			}
		}



	}	
	
?>