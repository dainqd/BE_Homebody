# Luồng hoạt động dự án :

## User

### Đăng ký/đăng nhập tài khoản

1. Đăng ký tài khoản
    - Api đăng ký tài khoản:
    - Body đăng ký tài khoản:
2. Đăng nhập tài khoản
    - Api đăng nhập tài khoản:
    - Body đăng nhập tài khoản:

### Tìm kiếm, xem đối tác cần đặt trước

    - Api tìm kiếm, lấy danh sách đối tác
    - Tìm kiếm đối tác theo vị trí, khu vực

### Chọn  dịch vụ mong muốn

    - Xem chi tiết đối tác, bao gồm các dịch vụ hiện có, thông tin chi tiết, các đánh giá

### Đặt trước dịch vụ

    - Chọn dịch vụ mong muốn, chọn thời gian, tên, địa chỉ để xác nhận thanh toán

### Thanh toán
    - Thanh toán đặt trước(cần generate ra một token cho stripe)
    - Api thanh toán đặt trước:/api/checkout/create
    - Body thanh toán đặt trước(Lưu ý gửi kèm token của user):
      JSON {
        "description": "Thanh toán đơn hàng",
        "stripeToken": "tok_1J0mYx2c3j3zCp5",
        "booking_id": "1"
    }
    
    - Url test thanh toán: https://homebodyglam.com/test
    - Url generate token thanh toán: https://homebodyglam.com/stripe-token
    
