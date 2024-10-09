/*
 Navicat Premium Dump SQL

 Source Server         : koneksi_karyawan
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : presigngps

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 09/10/2024 21:16:36
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for departemen
-- ----------------------------
DROP TABLE IF EXISTS `departemen`;
CREATE TABLE `departemen`  (
  `kode_dept` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_dept` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`kode_dept`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of departemen
-- ----------------------------
INSERT INTO `departemen` VALUES ('BOD', 'Board Of Director', '2024-09-28 15:02:03', '2024-09-28 14:58:25');
INSERT INTO `departemen` VALUES ('HRD', 'Human Resource Devolopments', '2024-09-28 13:23:17', NULL);
INSERT INTO `departemen` VALUES ('IT', 'Information Technology', '2024-09-28 03:27:08', NULL);
INSERT INTO `departemen` VALUES ('MKT', 'Sales', '2024-10-06 20:03:22', NULL);

-- ----------------------------
-- Table structure for karyawan
-- ----------------------------
DROP TABLE IF EXISTS `karyawan`;
CREATE TABLE `karyawan`  (
  `nik` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jabatan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `kode_dept` char(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `remember_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`nik`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of karyawan
-- ----------------------------
INSERT INTO `karyawan` VALUES ('12344', 'Irfan Noor Asyikin', 'CEO', '08999999945', NULL, 'BOD', '$2y$10$jzV0I/0ha4NnCjWDhjf/..5JezuunKniyps/Q5IiinGIhyjccBQju', NULL);
INSERT INTO `karyawan` VALUES ('12345', 'Risma Indah Laulia', 'Costumer Service', '085745564646', '12345.jpg', 'MKT', '$2y$10$Hg7plxzl5bVIjTs8GcXPqeTkp1QlpNbrBxER9Jo7saSsBknmxBxzi', NULL);
INSERT INTO `karyawan` VALUES ('12347', 'Debby', 'Costumer Service', '08999999998', NULL, 'HRD', '$2y$10$gIausLxDf.khwecwFMNE8eHHVOB3PYgEtys5AfAuvrqZVXMrYHGt6', NULL);
INSERT INTO `karyawan` VALUES ('12348', 'Ni Komang Widiatmika', 'Costumer Service', '08999999999', '12348.jpg', 'MKT', '$2y$10$85L1qBe.s8k2fX.bN.dYA.FiPY/Av//G5ulPLNR1b3e7Ar6ycPz6W', NULL);
INSERT INTO `karyawan` VALUES ('12349', 'Adis Maylinda', 'Finance', '08999999997', '12349.png', 'MKT', '$2y$10$dbExLCTz8plwbsfe/c3ZFuEJFifTB4n/069Yq9rfGwssM9sKCVv/W', NULL);
INSERT INTO `karyawan` VALUES ('63720', 'Adeetz', 'Manager', '085828134382', '63720.jpg', 'HRD', '$2y$10$9PNOEmEKScsQ02z9GV.wSuIUwL5rr1bWNIK6RNn2nKtLqA/VcHcj2', NULL);

-- ----------------------------
-- Table structure for konfigurasi_lokasi
-- ----------------------------
DROP TABLE IF EXISTS `konfigurasi_lokasi`;
CREATE TABLE `konfigurasi_lokasi`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `lokasi_kantor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `radius` smallint NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of konfigurasi_lokasi
-- ----------------------------
INSERT INTO `konfigurasi_lokasi` VALUES (1, '-3.4454200569823943, 114.81241531868581', 90);

-- ----------------------------
-- Table structure for pengajuan_izin
-- ----------------------------
DROP TABLE IF EXISTS `pengajuan_izin`;
CREATE TABLE `pengajuan_izin`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nik` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tgl_izin` date NULL DEFAULT NULL,
  `status` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'i:izin s:sakit',
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status_approved` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '0:Pending 1:Disetujui 2:Ditolak',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pengajuan_izin
-- ----------------------------
INSERT INTO `pengajuan_izin` VALUES (1, '12345', '2024-09-24', 'i', 'Ada Acara dirumah', '1');
INSERT INTO `pengajuan_izin` VALUES (2, '12345', '2024-09-24', 's', 'Maag', '0');
INSERT INTO `pengajuan_izin` VALUES (3, '12345', '2024-09-24', 'i', 'Kerumah saudara', '2');
INSERT INTO `pengajuan_izin` VALUES (4, '63720', '2024-09-24', 's', 'Kanker', '2');
INSERT INTO `pengajuan_izin` VALUES (5, '63720', '2024-10-10', 'i', 'ada kondangan keluarga', '0');
INSERT INTO `pengajuan_izin` VALUES (6, '63720', '2024-10-15', 's', 'Sakit hati', '1');

-- ----------------------------
-- Table structure for presensi
-- ----------------------------
DROP TABLE IF EXISTS `presensi`;
CREATE TABLE `presensi`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nik` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_presensi` date NOT NULL,
  `jam_in` time NOT NULL,
  `jam_out` time NULL DEFAULT NULL,
  `foto_in` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto_out` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `lokasi_in` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lokasi_out` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 971133 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of presensi
-- ----------------------------
INSERT INTO `presensi` VALUES (971111, '63720', '2024-09-20', '10:59:40', '03:59:58', '63720_2024-09-20_in.png', '63720_2024-09-20_out.png', '-3.444703,114.8211279', '-3.444703,114.8211279');
INSERT INTO `presensi` VALUES (971112, '63720', '2024-09-21', '02:05:00', '08:15:23', '63720_2024-09-21_in.png', '63720_2024-09-21_out.png', '-2.70336,111.6307456', '-2.70336,111.6307456');
INSERT INTO `presensi` VALUES (971113, '12345', '2024-09-24', '09:09:15', '16:59:59', '12345_2024-09-22_in.png', '63720_2024-09-21_out.png', '-2.8606464,114.5143296', '-2.70336,111.6307456');
INSERT INTO `presensi` VALUES (971114, '63720', '2024-09-24', '07:12:03', '07:12:31', '63720_2024-09-24_in.png', '63720_2024-09-24_out.png', '-3.4450189,114.8025802', '-3.4450189,114.8025802');
INSERT INTO `presensi` VALUES (971115, '63720', '2024-09-25', '02:03:13', '02:03:14', '63720_2024-09-25_in.png', '63720_2024-09-25_out.png', '-2.2151168,113.9081216', '-2.2151168,113.9081216');
INSERT INTO `presensi` VALUES (971116, '63720', '2024-09-26', '00:35:57', '08:46:27', '63720_2024-09-26_in.png', '63720_2024-09-26_out.png', '-3.444703,114.8211279', '-3.326,114.5899');
INSERT INTO `presensi` VALUES (971117, '63720', '2024-09-27', '05:05:13', '07:52:59', '63720_2024-09-27_in.png', '63720_2024-09-27_out.png', '-3.3168,114.5896', '-3.3168,114.5896');
INSERT INTO `presensi` VALUES (971118, '63720', '2024-09-28', '09:45:38', '09:45:52', '63720_2024-09-28_in.png', '63720_2024-09-28_out.png', '-3.3168,114.5896', '-3.3168,114.5896');
INSERT INTO `presensi` VALUES (971122, '63720', '2024-09-30', '16:49:52', NULL, '63720_2024-09-30_in.png', NULL, '-3.3168,114.5896', NULL);
INSERT INTO `presensi` VALUES (971123, '63720', '2024-10-01', '08:42:54', '17:22:25', '63720_2024-10-01_in.png', '63720_2024-10-01_out.png', '-2.9425,114.7079', '-2.9425,114.7079');
INSERT INTO `presensi` VALUES (971124, '63720', '2024-10-03', '11:08:32', '17:00:46', '63720_2024-10-03_in.png', '63720_2024-10-03_out.png', '-2.9425,114.7079', '-3.3168,114.5896');
INSERT INTO `presensi` VALUES (971125, '63720', '2024-10-04', '16:23:35', '16:23:47', '63720_2024-10-04_in.png', '63720_2024-10-04_out.png', '-2.2052,113.9391', '-2.2052,113.9391');
INSERT INTO `presensi` VALUES (971126, '63720', '2024-10-05', '08:32:37', '16:55:58', '63720_2024-10-05_in.png', '63720_2024-10-05_out.png', '-2.2052,113.9391', '-2.2052,113.9391');
INSERT INTO `presensi` VALUES (971129, '63720', '2024-10-06', '13:27:36', '16:06:21', '63720_2024-10-06_in.png', '63720_2024-10-06_out.png', '-3.4459612777355657,114.81298091931104', '-3.4459612777355657,114.81298091931104');
INSERT INTO `presensi` VALUES (971130, '63720', '2024-10-07', '08:08:56', NULL, '63720_2024-10-07_in.png', NULL, '-3.4459612777355657,114.81298091931104', NULL);
INSERT INTO `presensi` VALUES (971131, '63720', '2024-10-08', '17:29:33', '17:30:46', '63720_2024-10-08_in.png', '63720_2024-10-08_out.png', '-3.4459612777355657,114.81298091931104', '-3.4459612777355657,114.81298091931104');
INSERT INTO `presensi` VALUES (971132, '63720', '2024-10-09', '07:31:52', '19:10:17', '63720_2024-10-09_in.png', '63720_2024-10-09_out.png', '-3.4459612777355657,114.81298091931104', '-3.4459612777355657,114.81298091931104');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (2, 'Super Admin', 'admin@gmail.com', NULL, '$2y$10$ypdOkIBwI3l88olmItg9w.7/XIvhge0nT0OIwwjeVl96wCrSzWlii', 'XuEuIfkdfQe56P9dCgQS2UvzbRiVSoEZLUig1BPykK86cevfNNzHByGZtIhz', '2024-09-23 08:39:44', '2024-09-23 08:39:44');

-- ----------------------------
-- View structure for cetakrekap
-- ----------------------------
DROP VIEW IF EXISTS `cetakrekap`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `cetakrekap` AS SELECT  
    presensi.nik,
    karyawan.nama_lengkap,
    MAX(IF(DAY(tgl_presensi) = 1, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_1,
    MAX(IF(DAY(tgl_presensi) = 2, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_2,
    MAX(IF(DAY(tgl_presensi) = 3, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_3,
    MAX(IF(DAY(tgl_presensi) = 4, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_4,
    MAX(IF(DAY(tgl_presensi) = 5, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_5,
    MAX(IF(DAY(tgl_presensi) = 6, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_6,
    MAX(IF(DAY(tgl_presensi) = 7, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_7,
    MAX(IF(DAY(tgl_presensi) = 8, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_8,
    MAX(IF(DAY(tgl_presensi) = 9, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_9,
    MAX(IF(DAY(tgl_presensi) = 10, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_10,
    MAX(IF(DAY(tgl_presensi) = 11, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_11,
    MAX(IF(DAY(tgl_presensi) = 12, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_12,
    MAX(IF(DAY(tgl_presensi) = 13, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_13,
    MAX(IF(DAY(tgl_presensi) = 14, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_14,
    MAX(IF(DAY(tgl_presensi) = 15, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_15,
    MAX(IF(DAY(tgl_presensi) = 16, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_16,
    MAX(IF(DAY(tgl_presensi) = 17, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_17,
    MAX(IF(DAY(tgl_presensi) = 18, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_18,
    MAX(IF(DAY(tgl_presensi) = 19, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_19,
    MAX(IF(DAY(tgl_presensi) = 20, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_20,
    MAX(IF(DAY(tgl_presensi) = 21, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_21,
    MAX(IF(DAY(tgl_presensi) = 22, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_22,
    MAX(IF(DAY(tgl_presensi) = 23, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_23,
    MAX(IF(DAY(tgl_presensi) = 24, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_24,
    MAX(IF(DAY(tgl_presensi) = 25, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_25,
    MAX(IF(DAY(tgl_presensi) = 26, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_26,
    MAX(IF(DAY(tgl_presensi) = 27, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_27,
    MAX(IF(DAY(tgl_presensi) = 28, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_28,
    MAX(IF(DAY(tgl_presensi) = 28, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_29,
    MAX(IF(DAY(tgl_presensi) = 28, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_30,
    MAX(IF(DAY(tgl_presensi) = 28, CONCAT(jam_in, "-", IFNULL(jam_out, "00:00:00")), "")) AS tgl_31
FROM 
    presensi
INNER JOIN 
    karyawan ON presensi.nik = karyawan.nik
WHERE 
    MONTH(tgl_presensi) = 9
    AND YEAR(tgl_presensi) = 2024
GROUP BY 
    presensi.nik, karyawan.nama_lengkap ;

SET FOREIGN_KEY_CHECKS = 1;
