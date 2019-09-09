<?php


                     
              use \Magento\Framework\App\Bootstrap;

                        require dirname(__DIR__). '/app/bootstrap.php';
                        $params = $_SERVER;

                        $bootstrap = Bootstrap::create(BP, $params);

                        $obj = $bootstrap->getObjectManager();
                        $state = $obj->get('Magento\Framework\App\State');
                        $state->setAreaCode('frontend');


                        $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
                        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                        $connection = $resource->getConnection(); 

                          $sql = "SELECT * FROM sales_order_item WHERE created_at > DATE_SUB(NOW(),INTERVAL 1 HOUR) and sprice IS NULL;" ; 
                          $result = $connection->fetchAll($sql);
                          echo ' Total records found:'.count($result);
                          foreach ($result as $key => $value) {  
                              $productObj = $objectManager->create('Magento\Catalog\Model\Product')->load($value['product_id']); 
                            if($productObj->getSpecialPrice()>0){                                                                                       
                                $secondQuery = 'update sales_order_item set sprice='.$productObj->getSpecialPrice().' where item_id='.$value["item_id"].' ';
                                $connection->query($secondQuery); 
                                echo ' SKU:'.$value["sku"].' Sprice:'.$productObj->getSpecialPrice().' Saved!';
                            }
                          }
                          echo ' Script END:'; 