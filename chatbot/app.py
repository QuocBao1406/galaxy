# app.py - Phiên bản nâng cấp, gọi Google Gemini API

from flask import Flask, request, jsonify
from flask_cors import CORS
import google.generativeai as genai
import os # Thư viện để làm việc với biến môi trường

app = Flask(__name__)
CORS(app) # Cho phép frontend (index.html) gọi backend này

# --- CẤU HÌNH GOOGLE GEMINI API KEY ---
# CÁCH TỐT NHẤT: Đặt API key của bạn vào một biến môi trường tên là GOOGLE_API_KEY.
#   Ví dụ, trong terminal:
#   Linux/macOS: export GOOGLE_API_KEY="YOUR_API_KEY_HERE"
#   Windows (PowerShell): $env:GOOGLE_API_KEY="YOUR_API_KEY_HERE"
#   Windows (CMD): set GOOGLE_API_KEY="YOUR_API_KEY_HERE"
# Sau đó, code sẽ tự động đọc nó.

# CÁCH KHÁC (CHỈ DÙNG CHO DEMO NÀY, KHÔNG AN TOÀN CHO PRODUCTION):
# Thay thế "YOUR_API_KEY_HERE" bằng API Key thực của bạn.
# TUYỆT ĐỐI KHÔNG ĐƯA API KEY THẬT LÊN GITHUB HAY CHIA SẺ CÔNG KHAI.
GEMINI_API_KEY = "AIzaSyCYYAd0OfJG5UMN3mVGLUzLN4unrozLbBY" # <<< !!! THAY THẾ BẰNG API KEY CỦA BẠN !!!

try:
    # Cố gắng lấy API key từ biến môi trường trước (an toàn hơn)
    configured_api_key = os.environ.get("GOOGLE_API_KEY")
    if not configured_api_key:
        print("Thông báo: Không tìm thấy biến môi trường GOOGLE_API_KEY. Sử dụng API key được hardcode (chỉ cho demo).")
        configured_api_key = GEMINI_API_KEY # Dùng key hardcode nếu không có biến môi trường

    if configured_api_key == "YOUR_API_KEY_HERE" or not configured_api_key: # Kiểm tra nếu key vẫn là placeholder hoặc rỗng
        print("-" * 70)
        print("CẢNH BÁO QUAN TRỌNG: API KEY CHƯA ĐƯỢC CẤU HÌNH ĐÚNG CÁCH!")
        print("Bạn đang sử dụng API key placeholder ('YOUR_API_KEY_HERE') hoặc API key rỗng.")
        print("Chatbot sẽ KHÔNG thể kết nối với Google Gemini và sẽ không hoạt động như mong đợi.")
        print("Vui lòng:")
        print("1. Thay thế 'YOUR_API_KEY_HERE' trong file app.py bằng API key thật của bạn từ Google AI Studio.")
        print("HOẶC (Khuyến nghị hơn):")
        print("2. Đặt biến môi trường GOOGLE_API_KEY với giá trị API key thật của bạn.")
        print("-" * 70)
        model = None # Đặt model là None để các hàm sau biết và xử lý
    else:
        genai.configure(api_key=configured_api_key)
        # Khởi tạo model - bạn có thể chọn model phù hợp, ví dụ: 'gemini-1.5-flash-latest' cho tốc độ
        model = genai.GenerativeModel('gemini-1.5-flash-latest')
        print("Đã cấu hình và khởi tạo Google Gemini Model thành công!")
except Exception as e:
    print(f"LỖI NGHIÊM TRỌNG: Không thể cấu hình hoặc khởi tạo Google Gemini Model: {e}")
    print("Hãy đảm bảo bạn đã cài đặt thư viện 'google-generativeai' và cung cấp API key hợp lệ.")
    model = None

def goi_gemini_cho_chatbot_vu_tru(cau_hoi_nguoi_dung):
    """
    Hàm này gửi câu hỏi đến Google Gemini API và trả về câu trả lời.
    """
    if model is None: # Kiểm tra nếu model chưa được khởi tạo thành công
        return ("Xin lỗi, có vẻ như 'bộ não vũ trụ' của tôi chưa được kết nối đúng cách. "
                "Vui lòng kiểm tra lại cấu hình API key trong file app.py hoặc biến môi trường.")

    print(f"Backend nhận được câu hỏi: '{cau_hoi_nguoi_dung}'. Đang gửi tới Gemini...")

    # Thiết kế prompt (câu lệnh gợi ý) cho Gemini
    # Prompt này hướng dẫn Gemini hành xử như một chuyên gia vũ trụ
    prompt_cho_gemini = f"""Bạn là một chatbot trợ lý ảo thông thái, chuyên gia hàng đầu về vũ trụ và thiên văn học của trang web này và trang web này đang tích hợp bạn, tên bạn là GALAXY_BOT.
    Nhiệm vụ của bạn là cung cấp những câu trả lời chính xác, chi tiết, dễ hiểu và thú vị bằng tiếng Việt cho các câu hỏi liên quan đến vũ trụ.
    Hãy sử dụng kiến thức sâu rộng của mình để giải đáp thắc mắc sau:

    Câu hỏi từ người dùng: "{cau_hoi_nguoi_dung}"

    Câu trả lời của bạn:"""

    try:
        # Gửi yêu cầu đến Gemini
        response = model.generate_content(prompt_cho_gemini)

        # Kiểm tra xem có nội dung trả về không (có thể bị chặn bởi bộ lọc an toàn)
        if response.parts:
            cau_tra_loi_tu_gemini = response.text
            print(f"Gemini đã trả lời (một phần): '{cau_tra_loi_tu_gemini[:100]}...'") # Log 100 ký tự đầu
            return cau_tra_loi_tu_gemini
        else:
            # Xử lý trường hợp không có text trong response
            feedback_info = "Không có thông tin phản hồi cụ thể."
            if hasattr(response, 'prompt_feedback') and response.prompt_feedback:
                feedback_info = str(response.prompt_feedback)
            print(f"CẢNH BÁO: Phản hồi từ Gemini không chứa nội dung text. Có thể do bị chặn bởi cài đặt an toàn. Feedback: {feedback_info}")
            return ("Rất tiếc, tôi không thể tạo ra câu trả lời cho câu hỏi này vào lúc này. "
                    "Có thể nội dung bạn yêu cầu không phù hợp hoặc đã bị chặn vì lý do an toàn. "
                   f"Thông tin phản hồi (nếu có): {feedback_info}")

    except Exception as e:
        print(f"LỖI khi gọi Gemini API: {e}")
        # Cung cấp thông tin lỗi chi tiết hơn một chút cho việc debug
        return f"Xin lỗi, đã có sự cố kỹ thuật khi tôi cố gắng kết nối với trung tâm kiến thức vũ trụ. Lỗi: {type(e).__name__} - {str(e)}"

@app.route('/chat', methods=['POST'])
def xu_ly_chat():
    try:
        data = request.get_json()
        if not data or 'message' not in data:
            return jsonify({"error": "Yêu cầu không hợp lệ, thiếu trường 'message'"}), 400

        user_message = data['message']
        bot_reply = goi_gemini_cho_chatbot_vu_tru(user_message) # Sử dụng hàm mới gọi Gemini

        return jsonify({"reply": bot_reply})
    except Exception as e:
        print(f"Lỗi trong quá trình xử lý request /chat: {e}")
        return jsonify({"error": "Có lỗi nghiêm trọng xảy ra phía máy chủ"}), 500

@app.route('/') # Route cơ bản để kiểm tra backend có chạy không
def health_check():
    if model:
        return "Backend chatbot vũ trụ (kết nối Gemini) đang hoạt động! Model đã được tải."
    else:
        return "Backend chatbot vũ trụ đang hoạt động, NHƯNG CÓ VẤN ĐỀ VỚI VIỆC TẢI MODEL GEMINI. Hãy kiểm tra API key và lỗi trong console."

if __name__ == '__main__':
    print("*" * 70)
    print("Máy chủ Flask backend (phiên bản thông minh hơn với Gemini)")
    print("Đang khởi chạy tại http://127.0.0.1:5000")
    print("Hãy đảm bảo bạn đã thay thế 'YOUR_API_KEY_HERE' bằng API Key thật")
    print("hoặc đã đặt biến môi trường GOOGLE_API_KEY.")
    print("Mở file 'index.html' trong trình duyệt của bạn để bắt đầu chat.")
    print("Truy cập http://127.0.0.1:5000/ trong trình duyệt để kiểm tra trạng thái backend.")
    print("*" * 70)
    app.run(host='127.0.0.1', port=5000, debug=True) # debug=True chỉ dùng khi phát triển