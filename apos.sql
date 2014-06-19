-- phpMyAdmin SQL Dump
-- version 2.8.0.1
-- http://www.phpmyadmin.net
-- 
-- โฮสต์: custsql-ipg49.eigbox.net
-- เวลาในการสร้าง: 24 ม.ค. 2014  00:49น.
-- รุ่นของเซิร์ฟเวอร์: 5.5.32
-- รุ่นของ PHP: 4.4.9
-- 
-- ฐานข้อมูล: `apos`
-- 

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `bank`
-- refer  purchase invoid receipt order

CREATE TABLE `bank` (
  `bankaccount` varchar(15) NOT NULL,
  `bank_name` varchar(30) NOT NULL, -- ชื่อธนาคาร
  `brandbank` varchar(30) NOT NULL, -- สาขา
  `deposit` float DEFAULT NULL, -- ฝาก
  `withdraw` float DEFAULT NULL, -- ถอน
  `trans_date` datetime NOT NULL, -- วันทำรายการ 
  `purid` varchar(15) DEFAULT NULL, -- เลขที่ใบสั่งซื้อ
  `invid` varchar(15) DEFAULT NULL, -- เลขที่ใบรับสินค้า
  `receipt` varchar(15) DEFAULT NULL, -- เลขที่ใบเสร็จ
  `orderid` varchar(15) DEFAULT NULL, -- เลขที่ใบจองสินค้า
  PRIMARY KEY (`bankaccount`,`trans_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='ตารางบัญชีธนาคาร';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `casher`
-- refer user receipt

CREATE TABLE `casher` (
  `userid` varchar(15) NOT NULL, -- เลขที่ผู้ใช้งาน
  `rec_time` datetime NOT NULL, -- วันเวลารับเงิน
  `start_money` float DEFAULT NULL, -- จำนวนเงินเริ่มต้น
  `end_money` float DEFAULT NULL, -- จำนวนเงินสิ้นสุด
  `recid` varchar(15) NOT NULL -- เลขที่ใบเสร็จ
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='ผู้รับเงิน';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `catalog`
-- refer item

CREATE TABLE `catalog_item` (
  `itemid` varchar(15) NOT NULL, -- รหัสสินค้า
  `catalog_name` varchar(30) NOT NULL, -- ประเภทสินค้า 
  `master_catalog` varchar(30) NOT NULL, -- ประเภทสินค้าหลัก
  `master_type` varchar(1) DEFAULT NULL, -- ตัวเช็คสถานะมาสเตอร์
  PRIMARY KEY (`itemid`,`catalog_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='ประเภทสินค้า';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `count_name`
-- refer item

CREATE TABLE `count_name` (
  `itmid` varchar(15) NOT NULL,-- รหัสสินค้า
  `name_count1` varchar(20) NOT NULL, -- ชื่อการนับต้น 
  `count1` int(11) DEFAULT NULL,-- จำนวนหน่วยต้น
  `name_count2` varchar(20) NOT NULL, -- ชื่อหน่วยนับปลาย
  `count2` int(11) NOT NULL, -- จำนวนหน่วยนับปลาย
  PRIMARY KEY (`itmid`,`name_count1`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='ตารางคำนวณจำนวนนับ';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `customer`
--  refer receipt order  customertype

CREATE TABLE `customer` (
  `cusid` varchar(15) NOT NULL, -- รหัสลูกค้า
  `name` varchar(30) NOT NULL, -- ชื่อลูกค้า
  `suname` varchar(30) DEFAULT NULL, -- นามสกุล
  `tel1` varchar(15) DEFAULT NULL, -- โทรศัพท์
  `tel2` varchar(15) DEFAULT NULL, -- โทรศัพท์2
  `fax` varchar(15) DEFAULT NULL, -- แฟกซ์
  `address1` varchar(50) DEFAULT NULL, -- ที่อยู่บรรทัดแรก
  `address2` varchar(50) DEFAULT NULL, -- ที่อยู่บรรทัดถัดมา
  `province` varchar(30) DEFAULT NULL, -- จังหวัด
  `post` varchar(10) DEFAULT NULL, -- รหัสไปรษณีย์
  `cutid` varchar(15) NOT NULL, -- ประเภทลูกค้า
  `email` varchar(30) DEFAULT NULL, -- อีเมล์
  `detail2` blob, -- รายละเอียดลูกค้า
  `image` varchar(30) DEFAULT NULL, -- รูปลูกค้า
  PRIMARY KEY (`cusid`),
  KEY `name` (`name`,`suname`) 
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='ตารางลูกค้า';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `customertype`
-- พrefer customer price

CREATE TABLE `customertype` (
  `cutid` varchar(15) NOT NULL, -- รหัสลูกค้า
  `customer_type_name` varchar(50) NOT NULL, -- ประเภทลูกค้า
  `other_comment` tinyblob, -- คำอธิบาย
  PRIMARY KEY (`cutid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='ตารางประเภทลูกค้า';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `invoid`
-- refer purchase invoid_detail bank supplier 

CREATE TABLE `invoid` (
  `invid` varchar(15) NOT NULL, -- เลขที่ใบรับสินค้า
  `bankaccount` varchar(15) DEFAULT NULL, -- เลขที่บัญชีที่จ่ายเงิน
  `purid` varchar(15) NOT NULL, -- เลขที่ใบสั่งซื้อ
  `inv_date` date NOT NULL, -- วันที่ใบรับสินค้า
  `paytype` varchar(15) NOT NULL, -- ประเภทการจ่ายเงิน
  `pay_date` date NOT NULL, -- วันจ่ายเงิน
  `supid` varchar(15) NOT NULL, -- รหัสผู้ส่งสินค้า
  `rec_date` date NOT NULL, -- วันที่รับสินค้า
  `totalprice` float NOT NULL, -- รวมเป็นเงิน
  `other_comment` text, -- คำอธิบาย
  PRIMARY KEY (`invid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='ใบรับสินค้า';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `invoid_detail`
--  refer invoid  item store 

CREATE TABLE `invoid_detail` (
  `invid` varchar(15) NOT NULL, --  เลขที่ใบรับสินค้า
  `itemid` varchar(15) NOT NULL, -- รหัสสินค้า
  `total` float NOT NULL, -- จำนวนสินค้า
  `price` float NOT NULL, -- ราคาต่อหน่วยสินค้า
  `totalprice` float NOT NULL, -- รวมเป็นมูลค่า
  PRIMARY KEY (`invid`,`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='รายละเอียดในรับสินค้า';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `item`
--  refer catalog count price invoid _detail purchase_detail store receipt_detail  order_detail

CREATE TABLE `item` (
  `itemid` varchar(15) NOT NULL, -- รหัสสินค้า
  `name` varchar(50) NOT NULL, -- ชื่อสินค้า
  `detail1` varchar(255) DEFAULT NULL, -- รายละเอียดสินค้าอย่างย่อ
  `detail2` text, -- รายละเอียดสินค้าอย่างละเอียด
  `start_date` date DEFAULT NULL, -- วันที่เริ่มจำหน่าย
  `end_date` date DEFAULT NULL, -- วันที่สิ้นสุดจำหน่าย
  `image` varchar(50) DEFAULT NULL, -- รูปสินค้า
  `barcode` varchar(20) DEFAULT NULL, -- รหัสบาร์โค้ด
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='ตารางรายชื่อสินค้า';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `preemption`
--  refer   receipt bank  receipt customer

CREATE TABLE `preemption` (
  `orderid` varchar(15) NOT NULL, -- เลขที่ใบสั่งจอง
  `cusid` varchar(15) NOT NULL, -- รหัสลูกค้า 
  `bankaccount` varchar(15) NOT NULL, -- เลขที่บัญชีธนาคารของลูกค้า
  `order_date` date NOT NULL, -- วันที่สั่งจอง
  `tran_date` date NOT NULL, -- วันที่ส่งสินค้า
  `pay_date` date NOT NULL, -- วันที่จ่ายค่าสินค้า
  `totalprice` float NOT NULL, -- รวมราคาสินค้า
  `paytype` varchar(15) NOT NULL, -- ประเภทการจ่ายเงิน
  `other_comment` text NOT NULL, -- คำอธิบายอื่นๆ
  PRIMARY KEY (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='ใบจองสินค้า';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `order_detail`
-- refer order itemid

CREATE TABLE `order_detail` (
  `orderid` varchar(15) NOT NULL, -- เลขที่ใบสั่งจอง
  `itemid` varchar(15) NOT NULL, -- รหัสสินค้า
  `total` float NOT NULL, -- จำนวนสินค้า
  `totalprice` float NOT NULL, -- รวมเป็นเงิน
  `price` float NOT NULL, -- ราคาสินค้า
  PRIMARY KEY (`orderid`,`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='รายละเอียดคำสั่งจองสิ้นค้า';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `permit`
--  refer  user

CREATE TABLE `permit` (
  `userid` varchar(15) NOT NULL, -- เลขที่ผู้ใช้งาน
  `cmenu` varchar(30) NOT NULL, -- เมนูรายการสินค้า
  `cread` char(1) DEFAULT NULL, -- สิทธิให้อ่าน
  `cprint` char(1) DEFAULT NULL, -- สิทธิให้พิมพ์
  `cwrite` char(1) DEFAULT NULL, -- สิทธิให้เขียน
  `cdelete` char(1) DEFAULT NULL, -- สิทธิให้ลบ
  `keydatabase` varchar(36) NOT NULL --  คีย์การเข้าข้อมูล
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='ตารางอนุญาต';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `price`
-- refer item customertype

CREATE TABLE `price` (
  `itemid` varchar(15) NOT NULL, -- รหัสสินค้า
  `price` float NOT NULL, -- ราคาสินค้า
  `discount` float NOT NULL, -- ส่วนลด ที่สามารถลดได้ต่ำสุด
  `cutid` varchar(15) NOT NULL, -- ประเภทลูกค้า
  `percent` float NOT NULL, -- ส่วนลดคิดเป็นเปอร์เซ็นต์
  `commentp` varchar(50) NOT NULL,  -- คำอธิบายราคาสินค้า
  PRIMARY KEY (`itemid`,`cutid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='ราคาสินค้า';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `purchase`
-- refer supplier purchase_detail  invoid bank purchase_detail

CREATE TABLE `purchase` (
  `supid` varchar(15) NOT NULL, -- รหัสผู้ส่งสินค้า
  `purid` varchar(15) NOT NULL, -- เลขที่ใบสั่งสินค้า
  `bankaccount` varchar(15) DEFAULT NULL, -- เลขที่ธนาคาร
  `pur_date` date NOT NULL, -- วันที่สั่งสินค้า
  `trans_price` float NOT NULL, -- วันที่ขนส่ง
  `pay_date` date NOT NULL, -- วันที่จ่ายเงิน
  `type_pay` varchar(15) NOT NULL, -- ประเภทการจ่ายเงิน
  `rec_date` date DEFAULT NULL, -- วันที่รับของ
  `totalprice` float NOT NULL, -- รวมเป็นเงิน
  PRIMARY KEY (`purid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='ใบคำสั่งซื้อ';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `purchase_detail`
-- refer purchase item 

CREATE TABLE `purchase_detail` (
  `purid` varchar(15) NOT NULL, -- เลขที่ใบสั่งซื้อ
  `itemid` varchar(15) NOT NULL, -- รหัสสินค้า
  `total` float NOT NULL, -- จำนวนสินค้า
  `price` float NOT NULL, -- ราคาสินค้า
  `totalprice` float NOT NULL, -- รวมเป็นเงิน
  PRIMARY KEY (`purid`,`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='รายละเอียดคำสั่งซื้อ';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `receipt`
 -- refer customer item order receipt_detail casher

CREATE TABLE `receipt` (
  `recid` varchar(15) NOT NULL, -- เลขที่ใบเสร็จ
  `bankaccount` varchar(15) DEFAULT NULL, -- เลขที่ธนาคาร
  `cusid` varchar(15) NOT NULL, -- รหัสลูกค้า
  `orderid` varchar(15) NOT NULL, -- เลขที่ใบสั่งจองสินค้า
  `rec_date` date NOT NULL, -- วันที่ออกใบเสร็จ
  `trans_date` date DEFAULT NULL, -- วันที่ขนส่ง
  `pay_date` date NOT NULL, -- วันที่รับเงิน
  `totalprice` float NOT NULL, -- รวมเป้นเงิน
  `paytype` varchar(15) NOT NULL, -- ประเภทการจ่ายเงิน
  `commentr` text, -- คำอธิบาย
  PRIMARY KEY (`recid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='ใบเสร็จ';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `receipt_detail`
-- refer receipt item  supplier

CREATE TABLE `receipt_detail` (
  `recid` varchar(15) NOT NULL, -- เลขที่ใบเสร็จ
  `itemid` varchar(15) NOT NULL, -- รหัสสินค้า
  `supid` varchar(15) NOT NULL, -- รหัสผู้ส่งสินค้า
  `totalprice` float NOT NULL, -- รวมเป็นเงิน
  `total` float NOT NULL, -- จำนวนสินค้า
  `price` float NOT NULL, -- ราคาสินค้า
  PRIMARY KEY (`recid`,`itemid`,`supid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='รายละเอียดใบเสร็จ';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `store`
-- refer item supplier invoid 

CREATE TABLE `store` (
  `itemid` varchar(15) NOT NULL, -- รหัสสินค้า
  `supid` varchar(15) NOT NULL, -- รหัสผู้ส่งสินค้า
  `in_date` date DEFAULT NULL, -- วันที่นำเข้าสินค้า
  `end_date` date DEFAULT NULL, -- วันหมดอายุ
  `total` float NOT NULL, -- จำนวนสินค้า
  `chang_date` date DEFAULT NULL, -- วันที่ขายล่าสุด
  `invid` varchar(15) NOT NULL,-- เลขที่ใบรับสินค้า
  PRIMARY KEY (`itemid`,`supid`,`invid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='สินค้าในสต๊อก';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `supplier`
--  refer purchase invoid store reciept_detail 


CREATE TABLE `supplier` (
  `supid` varchar(15)  NOT NULL, -- รหัสผู้ส่งสินค้า
  `sup_name` varchar(50) NOT NULL, -- ชื่อผู้ส่งสินค้า
  `tell` varchar(15) NOT NULL, -- โทรศัพท์
  `tell2` varchar(15) DEFAULT NULL, -- โทรศัพท์2
  `address1` varchar(50) DEFAULT NULL, -- ที่อยู่บรรทัดแรก
  `address2` varchar(50) DEFAULT NULL, -- ที่อยู่บรรทัด 2
  `image` varchar(15) DEFAULT NULL, -- รูปผู้ส่งสินค้า
  `sellman` varchar(80) NOT NULL, -- พนักงานขาย1 
  `sellman2` varchar(80) DEFAULT NULL, -- พนักงานขาย 2
  `account_bank` varchar(20) DEFAULT NULL, -- เลขที่ธนาคารผู้ส่งสินค้า
  PRIMARY KEY (`supid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='ตารางผู้ขายสิ้นค้าให้องค์กร';

-- --------------------------------------------------------

-- 
-- โครงสร้างตาราง `user`
--  refer userlog casher permit and all table

CREATE TABLE `user` (
  `userid` varchar(15) NOT NULL, -- รหัสผู้ใช้
  `name` varchar(30) NOT NULL, -- ชื่อผู้ใช้งาน
  `suname` varchar(50) NOT NULL, -- นามสกุล
  `personal_num` varchar(15) NOT NULL, -- หมายเลขประจำตัวผู้ใช้งาน
  `address1` varchar(50) NOT NULL, -- ที่อยู่ผู้ใช้งาน บรรทัดแรก
  `address2` varchar(50) NOT NULL, -- ที่อยู้ผู้ใช้งาน บรรทัด 2
  `province` varchar(30) NOT NULL, -- จังหวัด
  `post` varchar(10) NOT NULL, -- รหัสไปรษณีย์
  `tel` varchar(12) NOT NULL, -- โทรศัพท์
  `email` varchar(30) NOT NULL, -- อีเมล์
  `tel2` varchar(12) NOT NULL, -- โทรศัพท์2
  `password` varchar(30) NOT NULL, -- รหัสผู้ใช้งาน
  `userkey` varchar(32) NOT NULL -- รหัสผู้ใช้งาน
  
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='ผู้ใช้งาน';

-
INSERT INTO `user` VALUES ('1', 'test', '', '', '', '', '', '', '', 'aaa@k.com', '', 'aaa', '');
INSERT INTO `user` VALUES ('2', 'test', 'test su name', '', '', '', '', '', '', 'email@email.com', '', 'aaa', '');
INSERT INTO `user` VALUES ('3', 'testd', 'test sud name', '', '', '', '', '', '', 'emai2l@email.com', '', 'bbb', '');
