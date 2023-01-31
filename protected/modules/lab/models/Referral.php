<?php 

class Referral extends EActiveResource
     {
     /* The id that uniquely identifies a person. This attribute is not defined as a property      
      * because we don't want to send it back to the service like a name, surname or gender etc.
      */
     public $id;
     public $primaryKey;

     public static function model($className=__CLASS__)
     {
         return parent::model($className);
     }

     public function rest()
     {
        
         return CMap::mergeArray(
            parent::rest(),
            array(
                'resource'=>'referrals',
                'idProperty'=>'id',
                )
         );
     }

     /* Let's define some properties and their datatypes */
     public function properties()
     {
         return array(
         	 'id'=>array('type'=>'integer'),
             'referralCode'=>array('type'=>'string'),
             'referralDate'=>array('type'=>'string'),
             'referralTime'=>array('type'=>'string'),
             'receivingAgencyId'=>array('type'=>'integer'),
             'receivingAgency'=>array('type'=>'string'),
             'acceptingAgencyId'=>array('type'=>'integer'),
             'acceptingAgency'=>array('type'=>'string'),
             'lab_id'=>array('type'=>'integer'),
             'customer_id'=>array('type'=>'integer'),
             'paymentType_id'=>array('type'=>'integer'),
             'discount_id'=>array('type'=>'integer'),
             'discount'=>array('type'=>'integer'),
             'reportDue'=>array('type'=>'integer'),
             'conforme'=>array('type'=>'integer'),
             'receivedBy'=>array('type'=>'integer'),
             'status'=>array('type'=>'integer'),

             'total'=>array('type'=>'double'),
             'discountAmount'=>array('type'=>'double'),
             //'customer'=>array('type'=>'string'),
             'samples'=>array('type'=>'string'),
             'analyses'=>array('type'=>'string'),
         );
     }

     /* Define rules as usual */
     public function rules()
     {
         return array(
             array('id,referralCode,referralDate,referralTime,receivingAgencyId,receivingAgency,acceptingAgencyId,acceptingAgency,lab_id,customer_id,paymentType_id,discount_id,discount,reportDue,conforme,receivedBy,status, total, customer, samples','safe'),
             array('id,region_id','numerical','integerOnly'=>true),
             array('activated','boolean'),
             array('geo_location','numerical')
         );
     }

     /* Add some custom labels for forms etc. */
     public function attributeLabels()
     {
         return array(
             'referralCode'=>'Referral Code',
             'referralDate'=>'Referral Date',
             'referralTime'=>'Referral Time',
             'receivingAgencyId'=>'Referred By',
             'acceptingAgencyId'=>'Referred To',
             'lab_id'=>'Laboratory',
             'customer_id'=>'Customer',
             'paymentType_id'=>'Payment Type',
             'discount_id'=>'Discount',
             'reportDue'=>'Report Due',
             'conforme'=>'Conforme',
             'status'=>'Status',
         );
     }
    
    function getCustomer()
    {
        //$customer = Customer::model()->findById($this->customer_id);
        //return $customer;
        return 'hahaha';
    }
    // public function routes()
    // {
    //     return CMap::mergeArray(
    //             parent::routes(),
    //             array(
    //                 'posts'=>':lab/:referrals/:id/customer'
    //             )
    //     );
    // }
    // public function relations()
    // {
    //     return array(
    //         'customer'=>array(self::BELONGS_TO,'Customer','customer_id')
    //     );
    // }


    public function findReferralByAgency($agency_id)
    {
            $params=array('receivingAgencyId'=>$agency_id);
            $response=$this->query('collection','GET',$params); //Send a get request to the "collection" which is defined in your routes() method with the given params array. It defaults to "User" meaning an uri like http://api.yoursite.com/User
            return $this->populateRecords($response->getData()); //create an array of User models from the returned result
    }
    
    
    public static function getReferrals(){
        $referrals = '<script>
    
                rootRef = new Firebase("https://onelab-webservices-a4588.firebaseio.com");

                rootRef.authWithCustomToken("nCBkq9nILshXd18LnAKrBtFu37I3").catch(function(error) {
                  var errorCode = error.code;
                  var errorMessage = error.message;
                  
                });

                referrals = rootRef.child("referrals");
                referrals.on("value", function(snapshot) {
                  console.log(snapshot.val());
                }, function (errorObject) {
                  console.log("The read failed: " + errorObject.code);
                });

            </script>';
        
        //$dataProvider = new CArrayDataProvider($referrals);
        return $referrals;
    }
 }

 ?>