# PTIT_PTTM_HDV - Laravel Project Setup Guide

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Giới thiệu dự án

Dự án **PTIT_PTTM_HDV** được xây dựng trên Laravel framework - một framework PHP mạnh mẽ với cú pháp biểu cảm và thanh lịch. Laravel giúp việc phát triển web trở nên thú vị và sáng tạo bằng cách đơn giản hóa các tác vụ phổ biến.

## Yêu cầu hệ thống

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/PostgreSQL hoặc database khác được Laravel hỗ trợ

## Hướng dẫn cài đặt

### 1. Clone repository

```bash
git clone -b final-api-fe https://github.com/huykunptit/PTIT_PTTM_HDV.git
cd PTIT_PTTM_HDV
```

### 2. Cài đặt dependencies

#### Cài đặt PHP dependencies

```bash
# Cài đặt packages từ composer.json
composer install

# Cập nhật packages (nếu cần)
composer update
```

#### Cài đặt JavaScript dependencies

```bash
# Cài đặt packages từ package.json
npm install
```

### 3. Cấu hình môi trường

```bash
# Copy file .env.example thành .env
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Cấu hình database

Chỉnh sửa file `.env` với thông tin database của bạn:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Chạy migrations (nếu có)

```bash
php artisan migrate
```

## Chạy ứng dụng

### Khởi động Laravel server

```bash
# Chạy server trên port 8088
php artisan serve --port=8088
```

Ứng dụng sẽ chạy tại: `http://localhost:8088`

### Khởi động asset compilation

```bash
# Chạy Vite dev server cho hot reloading
npm run dev
```

### Các lệnh NPM khác

```bash
# Build assets cho production
npm run build

# Preview production build
npm run preview
```

## Cấu trúc dự án

```
PTIT_PTTM_HDV/
├── app/                 # Application logic
├── bootstrap/           # Framework bootstrap files
├── config/             # Configuration files
├── database/           # Migrations, factories, seeders
├── public/             # Public assets
├── resources/          # Views, CSS, JS
├── routes/             # Route definitions
├── storage/            # File storage
├── tests/              # Test files
└── vendor/             # Composer dependencies
```

## Tính năng chính của Laravel

- **Routing engine** đơn giản và nhanh chóng
- **Dependency injection container** mạnh mẽ
- Hỗ trợ multiple back-ends cho **session** và **cache**
- **Database ORM** (Eloquent) trực quan và biểu cảm
- **Schema migrations** không phụ thuộc vào database
- **Background job processing** mạnh mẽ
- **Real-time event broadcasting**

## Troubleshooting

### Lỗi thường gặp

1. **Permission denied**: 
```bash
sudo chmod -R 775 storage bootstrap/cache
```

2. **Key not set**:
```bash
php artisan key:generate
```

3. **Node modules issues**:
```bash
rm -rf node_modules package-lock.json
npm install
```

4. **Composer issues**:
```bash
composer clear-cache
composer install --no-cache
```

## Đóng góp

Cảm ơn bạn đã quan tâm đến việc đóng góp cho dự án! Vui lòng đọc hướng dẫn đóng góp trong [Laravel documentation](https://laravel.com/docs/contributions).

## Bảo mật

Nếu phát hiện lỗ hổng bảo mật, vui lòng gửi email đến team phát triển. Tất cả các lỗ hổng bảo mật sẽ được xử lý kịp thời.

## License

Dự án này được phân phối dưới [MIT license](https://opensource.org/licenses/MIT).

---

## Liên hệ

- GitHub: [huykunptit/PTIT_PTTM_HDV](https://github.com/huykunptit/PTIT_PTTM_HDV)
- Laravel Documentation: [https://laravel.com/docs](https://laravel.com/docs)
