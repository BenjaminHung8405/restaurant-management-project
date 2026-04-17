<!-- Reservation Modal Partial -->
<div id="reservation-modal" class="fixed inset-0 z-[80] hidden items-center justify-center bg-black/40 p-4 backdrop-blur-sm" onclick="event.target === this && closeReservationModal()" role="dialog" aria-modal="true" aria-label="Đặt bàn nhanh">
    <div class="relative max-h-[92dvh] w-full max-w-[800px] overflow-y-auto rounded-xl bg-white shadow-xl translate-y-4 opacity-0 transition-all duration-300" id="reservation-modal-content" onclick="event.stopPropagation()">
        <!-- Close Button -->
        <button type="button" onclick="closeReservationModal()" class="absolute right-4 top-4 inline-flex h-9 w-9 items-center justify-center rounded-full border border-neutral-200 text-neutral-500 transition-colors duration-200 hover:bg-neutral-100 hover:text-neutral-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 cursor-pointer">
            <i data-lucide="x" class="w-4.5 h-4.5"></i>
        </button>

        <!-- Success State (Hidden by default) -->
        <div id="reservation-success-state" class="hidden flex min-h-[360px] flex-col items-center justify-center gap-4 px-6 py-10 text-center sm:px-10">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-600">
                <i data-lucide="check" class="w-8 h-8"></i>
            </div>
            <h3 class="text-2xl font-semibold text-neutral-900">Đặt bàn thành công!</h3>
            <p class="max-w-md text-sm leading-6 text-neutral-600">
                Cảm ơn bạn đã đặt bàn. Chúng tôi sẽ liên hệ để xác nhận trong thời gian sớm nhất.
            </p>
            <button type="button" onclick="closeReservationModal()" class="mt-2 rounded-lg bg-green-600 px-8 py-3 text-sm font-semibold text-white shadow-sm transition-colors duration-200 hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 cursor-pointer">
                Đóng
            </button>
        </div>

        <!-- Form State -->
        <form id="reservation-form" class="px-6 py-8 sm:px-10" novalidate onsubmit="handleReservationSubmit(event)">
            <div class="mb-7 text-center">
                <h2 class="text-2xl font-semibold text-neutral-900">Đặt bàn nhanh</h2>
                <p class="mt-2 text-sm text-neutral-600">
                    Điền thông tin để giữ chỗ ngay tại nhà hàng.
                </p>
            </div>

            <div class="grid gap-4">
                <!-- Guest Name -->
                <div>
                    <label for="res_guest_name" class="mb-1 block text-sm font-medium text-neutral-700">Họ và tên</label>
                    <input id="res_guest_name" name="guest_name" type="text" placeholder="Tên của bạn" class="w-full rounded-lg border border-neutral-300 px-3 py-2.5 text-sm text-neutral-900 placeholder:text-neutral-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-500">
                    <p id="res_guest_name_error" class="hidden mt-1 text-xs text-red-600"></p>
                </div>

                <!-- Guest Phone -->
                <div>
                    <label for="res_guest_phone" class="mb-1 block text-sm font-medium text-neutral-700">Số điện thoại</label>
                    <input id="res_guest_phone" name="guest_phone" type="tel" placeholder="Ví dụ: 0901234567" class="w-full rounded-lg border border-neutral-300 px-3 py-2.5 text-sm text-neutral-900 placeholder:text-neutral-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-500">
                    <p id="res_guest_phone_hint" class="mt-1 text-xs text-neutral-500">Hỗ trợ định dạng 0xxxxxxxxx hoặc 84xxxxxxxxx.</p>
                    <p id="res_guest_phone_error" class="hidden mt-1 text-xs text-red-600"></p>
                </div>

                <!-- Date and Time -->
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="res_date_display" class="mb-1 block text-sm font-medium text-neutral-700">Ngày đặt bàn</label>
                        <input id="res_date_display" type="text" placeholder="dd/MM/yyyy" class="w-full rounded-lg border border-neutral-300 px-3 py-2.5 text-sm text-neutral-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-500">
                        <input type="hidden" id="res_reservation_date" name="reservation_date">
                        <p id="res_date_hint" class="mt-1 text-xs text-neutral-500">Định dạng dd/MM/yyyy.</p>
                        <p id="res_reservation_date_error" class="hidden mt-1 text-xs text-red-600"></p>
                    </div>

                    <div>
                        <label for="res_reservation_time" class="mb-1 block text-sm font-medium text-neutral-700">Giờ đặt bàn</label>
                        <select id="res_reservation_time" name="reservation_time" class="w-full rounded-lg border border-neutral-300 bg-white px-3 py-2.5 text-sm text-neutral-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 disabled:cursor-not-allowed disabled:bg-neutral-100">
                            <option value="">Chọn giờ đặt bàn</option>
                        </select>
                        <p id="res_time_hint" class="mt-1 text-xs text-neutral-500">Chúng tôi mở cửa 10:00 - 22:00.</p>
                        <p id="res_reservation_time_error" class="hidden mt-1 text-xs text-red-600"></p>
                    </div>
                </div>

                <!-- Party Size -->
                <div>
                    <label for="res_party_size" class="mb-1 block text-sm font-medium text-neutral-700">Số lượng khách</label>
                    <input id="res_party_size" name="party_size" type="number" min="1" value="2" class="w-full rounded-lg border border-neutral-300 px-3 py-2.5 text-sm text-neutral-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-500">
                    <p id="res_party_size_error" class="hidden mt-1 text-xs text-red-600"></p>
                </div>

                <!-- Notes -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="res_notes" class="block text-sm font-medium text-neutral-700">Ghi chú</label>
                        <span id="res_notes_count" class="text-xs text-neutral-500">0/300</span>
                    </div>
                    <textarea id="res_notes" name="notes" rows="3" maxlength="300" placeholder="Yêu cầu thêm (nếu có)" class="w-full resize-none rounded-lg border border-neutral-300 px-3 py-2.5 text-sm text-neutral-900 placeholder:text-neutral-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-500"></textarea>
                    <p id="res_notes_error" class="hidden mt-1 text-xs text-red-600"></p>
                </div>
            </div>

            <!-- Global Error -->
            <div id="res_global_error" class="hidden mt-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700" role="alert"></div>

            <button type="submit" id="res_submit_btn" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-orange-500 px-4 py-3 text-sm font-semibold text-white transition-all duration-200 hover:bg-orange-600 disabled:cursor-not-allowed disabled:opacity-70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 cursor-pointer">
                <span id="res_btn_text">Xác nhận đặt bàn</span>
                <span id="res_btn_spinner" class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/35 border-t-white"></span>
            </button>

            <p class="mt-3 text-center text-xs text-neutral-500">
                Mẹo: Nhấn <span class="font-medium">Esc</span> để đóng popup.
            </p>
        </form>
    </div>
</div>
