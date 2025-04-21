

 <!-- Coupon Modal -->
<div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="couponModalLabel">@lang('translation.CouponsforService')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Coupon Form -->
                <form id="couponForm">
                    @csrf
                    <input type="hidden" id="coupon_id" name="coupon_id">
                    <input type="hidden" id="coupon_service_id" name="service_id">
                    <div class="mb-3">
                        <label for="coupon_code" class="form-label">@lang('translation.CouponCode')</label>
                        <input type="text" class="form-control" id="coupon_code" name="code" required>
                    </div>

                    <div class="mb-3">
                        <label for="discount_amount" class="form-label">@lang('translation.DiscountAmount')</label>
                        <input type="number" class="form-control" id="discount_amount" name="discount_amount" required>
                    </div>

                    <div class="mb-3">
                        <label for="discount_type" class="form-label">@lang('translation.DiscountType')</label>
                        <select name="discount_type" id="discount_type" class="form-control" required>
                            <option value="percentage">@lang('translation.Percentage')</option>
                            <option value="fixed">@lang('translation.Fixed')</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">@lang('translation.ExpiryDate')</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>

                    <div class="mb-3">
                        <label for="maximum_usage" class="form-label">@lang('translation.MaximumUsage')</label>
                        <input type="number" class="form-control" id="maximum_usage" name="maximum_usage" min="1">
                    </div>

                    <div class="mb-3">
                        <label for="allow_maximum_usage" class="form-label">@lang('translation.AllowMaximumUsage')</label>

                        <!-- Hidden input to handle unchecked checkbox (sends 0) -->
                        <input type="hidden" name="allow_maximum_usage" value="0">

                        <!-- Actual checkbox input (sends 1 when checked) -->
                        <input type="checkbox" class="form-check-input" id="allow_maximum_usage" name="allow_maximum_usage" value="1">
                    </div>

                    <button type="submit" class="btn btn-primary">@lang('translation.SaveCoupon')</button>
                </form>

                <!-- Coupons Table -->
                <h4>@lang('translation.Coupons List')</h4>
                <table class="table table-bordered" id="couponTable">
                    <thead>
                        <tr>
                            <th>@lang('translation.No')</th>
                            <th>@lang('translation.CouponCode')</th>
                            <th>@lang('translation.DiscountAmount')</th>
                            <th>@lang('translation.ExpiryDate')</th>
                            <th>@lang('translation.Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Coupons will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Conditions and Policies Modal -->
<div class="modal fade" id="conditionsModal" tabindex="-1" aria-labelledby="conditionsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="conditionsModalLabel">@lang('translation.AddConditionsandPolicies')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- The form will be here, it will not be cleared when conditions are loaded -->
                <form action="{{ route('conditions.store') }}" method="post" id="conditionsForm" class="w-100" >
                    @csrf
                    <input type="hidden" name="service_id" id="conditions_service_id">
                    <input type="hidden" name="condition_id" id="condition_id">

                    <!-- Form fields for description and type -->
                    <div class="form-group">

                        <div class="form-group">
                            <div class="col-sm-6">
                                <label>@lang('translation.DescriptionAr')</label>
                                <textarea name="description_ar" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-sm-6">
                                <label>@lang('translation.DescriptionEn')</label>
                                <textarea name="description_en" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="type">نوع الشرط</label>
                            <select name="type" id="type" class="form-control">
                                <option value="payment">شرط الدفع</option>
                                <option value="service">شرط اضافه خدمه</option>
                                <option value="offer">شرط اضافه عرض</option>
                                <option value="premium_service">شرط خدمه مميزه</option>
                                <option value="service_request">شرط طلب خدمه</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">حفظ</button>
                </form>

                <div class="table-responsive"></div>
            </div>
        </div>
    </div>
</div>
