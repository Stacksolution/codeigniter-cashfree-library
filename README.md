#Cashfree

Cashfree Payments (www.cashfree.com) is a payments and banking technology company that enables businesses in India to collect payments online and make..

#Server Requirements


PHP version 5.6 or newer is recommended.

It should work on 5.3.7 as well, but we strongly advise you NOT to run
such old versions of PHP, because of potential security and performance
issues, as well as missing features.

#Installation
`$this->load->library('cashfree/Cashfree','cashfree');
 $config['cashfree_mode']       = 'production';
 $config['cashfree_app_id']     = $this->data['config']['cashfree_app_id'];
 $config['cashfree_app_secret'] = $this->data['config']['cashfree_app_secret'];
 $this->cashfree->initialize($config);
 $responce = $this->cashfree->_create_order($order_id,$amount);`

#Full impliment Blogs

-  `www.stackoverlode.com`
