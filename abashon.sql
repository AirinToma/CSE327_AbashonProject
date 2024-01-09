-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2022 at 08:45 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `abashon`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `name`, `email`, `message`) VALUES
(2, 'Shovon Khan', 'Shovon1994@yahoo.com', 'I think I was never the person who went out and had a lot of friends—I typically have a couple of close friends, and I rarely hang out in big groups. I was also always kind of a shy kid. I consider myself an introvert, but sometimes my friends would say, \\\'Well, no, you talk, and when you’re in a group, you’re part of the conversation!\\\' But typically, I am described as introverted. It doesn’t take a lot. I start feeling drained when I’m in a new environment with new people that I didn’t necessarily choose to be with. Sometimes, I just feel like the crowd isn’t for me. I recharge by spending time with some of my close friends, either one on one or with a couple of them. I also recharge by myself—since I’m an artist, I love to draw, paint, make music, and read. If it’s to a person that I feel I can trust, quite easy. But never to more than a couple people at a time. So I guess I’m only super open to a handful—I’m selective about it. It depends on the person I’m talking to, and the context. Sometimes, I love talking about school stuff, or just life stuff that everyone relates to. I definitely prefer deep talks over small talk!'),
(3, 'Lutfor Rahman', 'shovonkhn@gmail.com', 'I think I was never the person who went out and had a lot of friends—I typically have a couple of close friends, and I rarely hang out in big groups. I was also always kind of a shy kid. I consider myself an introvert, but sometimes my friends would say, \\\'Well, no, you talk, and when you’re in a group, you’re part of the conversation!\\\' But typically, I am described as introverted. It doesn’t take a lot. I start feeling drained when I’m in a new environment with new people that I didn’t necessarily choose to be with. Sometimes, I just feel like the crowd isn’t for me. I recharge by spending time with some of my close friends, either one on one or with a couple of them. I also recharge by myself—since I’m an artist, I love to draw, paint, make music, and read. If it’s to a person that I feel I can trust, quite easy. But never to more than a couple people at a time. So I guess I’m only super open to a handful—I’m selective about it. It depends on the person I’m talking to, and the context. Sometimes, I love talking about school stuff, or just life stuff that everyone relates to. I definitely prefer deep talks over small talk!'),
(4, 'Shovon Khan', 'Shovon1994@yahoo.com', 'I think I was never the person who went out and had a lot of friends—I typically have a couple of close friends, and I rarely hang out in big groups. I was also always kind of a shy kid. I consider myself an introvert, but sometimes my friends would say, \\\'Well, no, you talk, and when you’re in a group, you’re part of the conversation!\\\' But typically, I am described as introverted. It doesn’t take a lot. I start feeling drained when I’m in a new environment with new people that I didn’t necessarily choose to be with. Sometimes, I just feel like the crowd isn’t for me. I recharge by spending time with some of my close friends, either one on one or with a couple of them. I also recharge by myself—since I’m an artist, I love to draw, paint, make music, and read. If it’s to a person that I feel I can trust, quite easy. But never to more than a couple people at a time. So I guess I’m only super open to a handful—I’m selective about it. It depends on the person I’m talking to, and the context. Sometimes, I love talking about school stuff, or just life stuff that everyone relates to. I definitely prefer deep talks over small talk!'),
(5, 'Lutfor Rahman', 'shovonkhn@gmail.com', 'I think I was never the person who went out and had a lot of friends—I typically have a couple of close friends, and I rarely hang out in big groups. I was also always kind of a shy kid. I consider myself an introvert, but sometimes my friends would say, \\\'Well, no, you talk, and when you’re in a group, you’re part of the conversation!\\\' But typically, I am described as introverted. It doesn’t take a lot. I start feeling drained when I’m in a new environment with new people that I didn’t necessarily choose to be with. Sometimes, I just feel like the crowd isn’t for me. I recharge by spending time with some of my close friends, either one on one or with a couple of them. I also recharge by myself—since I’m an artist, I love to draw, paint, make music, and read. If it’s to a person that I feel I can trust, quite easy. But never to more than a couple people at a time. So I guess I’m only super open to a handful—I’m selective about it. It depends on the person I’m talking to, and the context. Sometimes, I love talking about school stuff, or just life stuff that everyone relates to. I definitely prefer deep talks over small talk!'),
(6, 'Shovon Khan', 'Shovon1994@yahoo.com', 'I think I was never the person who went out and had a lot of friends—I typically have a couple of close friends, and I rarely hang out in big groups. I was also always kind of a shy kid. I consider myself an introvert, but sometimes my friends would say, \\\'Well, no, you talk, and when you’re in a group, you’re part of the conversation!\\\' But typically, I am described as introverted. It doesn’t take a lot. I start feeling drained when I’m in a new environment with new people that I didn’t necessarily choose to be with. Sometimes, I just feel like the crowd isn’t for me. I recharge by spending time with some of my close friends, either one on one or with a couple of them. I also recharge by myself—since I’m an artist, I love to draw, paint, make music, and read. If it’s to a person that I feel I can trust, quite easy. But never to more than a couple people at a time. So I guess I’m only super open to a handful—I’m selective about it. It depends on the person I’m talking to, and the context. Sometimes, I love talking about school stuff, or just life stuff that everyone relates to. I definitely prefer deep talks over small talk!'),
(7, 'Shovon Khan', 'rahman.lutfor@northsouth.edu', 'Hi, this is a test');

-- --------------------------------------------------------

--
-- Table structure for table `house`
--

CREATE TABLE `house` (
  `house_id` int(6) NOT NULL,
  `block` varchar(1) NOT NULL,
  `road` int(2) NOT NULL,
  `number` int(5) NOT NULL,
  `floor` int(2) NOT NULL,
  `room_count` int(2) NOT NULL,
  `availability` varchar(15) NOT NULL DEFAULT 'Yes',
  `owner_id` int(5) NOT NULL,
  `date_posted` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `washroom_count` int(1) NOT NULL,
  `bed_count` int(1) NOT NULL,
  `generator` varchar(3) NOT NULL,
  `lift` varchar(3) NOT NULL,
  `image_link` varchar(255) NOT NULL,
  `area` int(255) NOT NULL,
  `lat` double NOT NULL,
  `longi` double NOT NULL,
  `description` text NOT NULL,
  `rent` int(255) NOT NULL,
  `tenant_id` int(255) NOT NULL DEFAULT 0,
  `tenant_name` varchar(255) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `image2` varchar(255) NOT NULL,
  `image3` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `house`
--

INSERT INTO `house` (`house_id`, `block`, `road`, `number`, `floor`, `room_count`, `availability`, `owner_id`, `date_posted`, `washroom_count`, `bed_count`, `generator`, `lift`, `image_link`, `area`, `lat`, `longi`, `description`, `rent`, `tenant_id`, `tenant_name`, `owner_name`, `image2`, `image3`, `name`) VALUES
(128, 'D', 17, 443, 1, 6, 'Yes', 46, '2022-04-18 08:54:16', 3, 4, 'No', 'Yes', 'https://images.pexels.com/photos/1457842/pexels-photo-1457842.jpeg', 1200, 23.815499407447728, 90.433240911084, '', 25000, 0, '', 'Shovon Khan', 'https://images.pexels.com/photos/106399/pexels-photo-106399.jpeg', 'https://images.pexels.com/photos/1396122/pexels-photo-1396122.jpeg', 'Radisson'),
(130, 'H', 4, 555, 8, 4, 'Yes', 46, '2022-04-18 08:54:21', 2, 2, 'Yes', 'Yes', 'https://images.pexels.com/photos/892618/pexels-photo-892618.jpeg', 700, 23.825667639938402, 90.43946363599855, '', 15000, 0, '', 'Shovon Khan', 'https://cdn.houseplansservices.com/product/63moi3e6vg5mbb8rmk0jcug42k/w620x413.jpg', 'https://i.pinimg.com/550x/fc/07/40/fc0740d7c26d93974e117cb88a81bc36.jpg', 'Nishorgo'),
(131, 'B', 3, 234, 4, 3, 'No', 47, '2022-04-18 08:54:34', 2, 2, 'No', 'Yes', 'https://static.dezeen.com/uploads/2020/02/house-in-the-landscape-niko-arcjitect-architecture-residential-russia-houses-khurtin_dezeen_2364_hero.jpg', 1234, 23.818253397161797, 90.43341257246095, '', 50000, 81, '', 'Lutfor Rahman', 'https://hgtvhome.sndimg.com/content/dam/images/hgtv/fullset/2019/2/7/3/BP_HHMTN310_Bolden_home-exterior_AFTER_0132.jpg', 'https://thumbs.dreamstime.com/b/house-exterior-8717154.jpg', 'Sea Crown'),
(133, 'M', 5, 324, 5, 6, 'Yes', 73, '2022-04-18 15:46:56', 2, 3, 'Yes', 'No', 'https://1.bp.blogspot.com/-6WTJe9fMvss/YO29ZEGox_I/AAAAAAAACMQ/YnY7GychDdkTIt0Hjdt29Lt2oTN36JpwACLcBGAsYHQ/s1424/IMG_20210713_232023.jpg', 2111, 23.820406955024897, 90.45710184248048, '10 mins walk from NSU', 40000, 0, '', 'Sheetal Khan', 'https://ichef.bbci.co.uk/news/976/cpsprodpb/17BAA/production/_117549179_whatsubject.jpg', 'https://assets-news.housing.com/news/wp-content/uploads/2022/01/11171226/World%E2%80%99s-15-Most-Beautiful-Houses-That-Will-Leave-You-Awestruck-02.png', 'Southbreeze'),
(136, 'F', 12, 132, 12, 12, 'Yes', 46, '2022-04-18 08:54:51', 12, 12, 'Yes', 'Yes', 'https://hgtvhome.sndimg.com/content/dam/images/hgtv/fullset/2019/8/1/1/uo2019_living-room-01-wide-blinds-up-KB2A8968_h.jpg.rend.hgtvcom.966.644.suffix/1564684055231.jpeg', 1234, 23.820388730410084, 90.43512918623048, '', 30000, 0, '', 'Shovon Khan', 'https://images.adsttc.com/media/images/5ecd/d4ac/b357/65c6/7300/009d/newsletter/02C.jpg', 'https://hips.hearstapps.com/hmg-prod.s3.amazonaws.com/images/brewster-mcleod-architects-1486154143.jpg', 'Nibir'),
(137, 'K', 6, 345, 5, 6, 'Yes', 46, '2022-04-18 08:55:08', 5, 4, 'No', 'Yes', 'https://thumbs.dreamstime.com/b/grey-lamp-bright-living-room-interior-poster-next-to-beige-sofa-real-photo-grey-lamp-bright-living-room-interior-118793825.jpg', 3344, 23.821035107718977, 90.44538595350343, '', 40000, 0, 'No one right now!', 'Shovon Khan', 'https://ychef.files.bbci.co.uk/976x549/p0bdlxm5.jpg', 'http://cdn.home-designing.com/wp-content/uploads/2017/05/forest-setting-lit-modern-two-storey-house-elevation.jpg', 'Somudra'),
(138, 'N', 4, 12, 2, 3, 'Yes', 46, '2022-04-18 09:03:26', 3, 3, 'Yes', 'Yes', 'https://i.postimg.cc/v8vyd5Dc/house.jpg', 1600, 23.813605958213852, 90.46812524792638, '', 39996, 0, '', 'Shovon Khan', 'https://media.iceportal.com/113891/photos/63612808_3XL.jpg', 'https://i.travelapi.com/hotels/2000000/1460000/1450300/1450238/40df0124_z.jpg', 'ELITEHOUSE'),
(139, 'A', 12, 1, 3, 4, 'Yes', 47, '2022-04-18 16:02:57', 4, 4, 'Yes', 'Yes', 'https://media.iceportal.com/113891/photos/63612808_3XL.jpg', 1600, 23.809910362921347, 90.42794960377913, 'Open space with amenities', 40000, 0, '', 'Lutfor Rahman', 'https://media.iceportal.com/113891/photos/63612808_3XL.jpg', 'https://media.iceportal.com/113891/photos/63612808_3XL.jpg', 'Sreemangal');

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(255) NOT NULL,
  `house_id` int(255) NOT NULL,
  `tenant_id` int(255) NOT NULL,
  `owner_id` int(255) NOT NULL,
  `txn_id` varchar(255) NOT NULL,
  `amount` int(255) NOT NULL,
  `time` date NOT NULL,
  `mer_txn` varchar(255) NOT NULL,
  `ongoing` varchar(3) NOT NULL DEFAULT 'Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `house_id`, `tenant_id`, `owner_id`, `txn_id`, `amount`, `time`, `mer_txn`, `ongoing`) VALUES
(31, 136, 81, 46, 'AAM1649427161591240', 30000, '2022-04-08', 'WEP-P2IOSHQE37', 'No'),
(32, 131, 81, 47, 'AAM1649430601591940', 50000, '2022-04-08', 'WEP-8NJ3LGOJXN', 'No'),
(33, 133, 81, 73, 'AAM1649431234591099', 40000, '2022-04-08', 'WEP-42IMSP90RV', 'No'),
(34, 133, 81, 73, 'AAM1649431745591297', 40000, '2022-04-08', 'WEP-EEGYB45B8F', 'No'),
(35, 131, 81, 47, 'AAM1649432013591105', 50000, '2022-04-08', 'WEP-8U8I2LTH13', 'Yes'),
(36, 136, 82, 46, 'AAM1649509861591476', 30000, '2022-04-09', 'WEP-VLHXOKR706', 'No'),
(37, 137, 81, 46, 'AAM1650122586591651', 40000, '2022-04-16', 'WEP-P7NKHPB3XA', 'No'),
(38, 137, 81, 46, 'AAM1650122586591651', 40000, '2022-04-16', 'WEP-P7NKHPB3XA', 'No'),
(39, 137, 81, 46, 'AAM1650122681591180', 40000, '2022-04-16', 'WEP-YOC4QEANVY', 'No'),
(40, 137, 81, 46, 'AAM1650122824591168', 40000, '2022-04-16', 'WEP-N6P1B1VFUS', 'No'),
(41, 137, 81, 46, 'AAM1650123421591158', 40000, '2022-04-16', 'WEP-NAJ4TKMURP', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(255) NOT NULL,
  `featured` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(6) NOT NULL,
  `user_type` varchar(10) NOT NULL,
  `name` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `gender` varchar(6) NOT NULL,
  `contact_no` varchar(15) NOT NULL,
  `user_id_creation` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_email` varchar(50) NOT NULL,
  `google_id` varchar(255) NOT NULL,
  `acc_confirm` varchar(255) NOT NULL,
  `confirm_status` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_type`, `name`, `password`, `gender`, `contact_no`, `user_id_creation`, `user_email`, `google_id`, `acc_confirm`, `confirm_status`) VALUES
(46, 'Owner', 'Shovon Khan', 'fa1b581e82c66ba41f9e8a98217fc947', 'Male', '01766097446', '2022-04-09 16:03:39', 'shovon1994@yahoo.com', '', '04661', 1),
(47, 'Owner', 'Lutfor Rahman', 'fa1b581e82c66ba41f9e8a98217fc947', 'Male', '1766097446', '2022-04-14 13:14:09', 'shovonkhn@gmail.com', '', '45845', 1),
(73, 'Owner', 'Sheetal Khan', '', 'Male', '1612746866', '2022-04-17 11:53:50', 'shovon3739@gmail.com', '112382410398640424851', '', 0),
(81, 'Tenant', 'K.M. Lutfor Rahman 1411162642', '', 'Male', '01612746866', '2022-04-13 19:08:32', 'Rahman.Lutfor@northsouth.edu', '115376806016004527791', '', 0),
(83, 'Tenant', 'Rumki', 'fa1b581e82c66ba41f9e8a98217fc947', 'Female', '01612746866', '2022-04-09 15:49:55', 'shovon3739@hotmail.com', '', '74786', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `house`
--
ALTER TABLE `house`
  ADD PRIMARY KEY (`house_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `house`
--
ALTER TABLE `house`
  MODIFY `house_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
