import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, useForm, router } from "@inertiajs/react";
import { useState } from "react";

export default function Show({
    auth,
    paymentSummary,
    shipmentProgress,
    purchaseOrder,
    goodsReceived,
}) {
    /*
    |--------------------------------------------------------------------------
    | STATE
    |--------------------------------------------------------------------------
    */
    const [showUploadForm, setShowUploadForm] = useState(false);
    const [showPaymentForm, setShowPaymentForm] = useState(false);
    const [showShipmentForm, setShowShipmentForm] = useState(false);

    /*
    |--------------------------------------------------------------------------
    | DOCUMENT FORM
    |--------------------------------------------------------------------------
    */
    const shipmentInputClass =
        "w-full bg-white text-gray-900 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500";
    const shipmentLabelClass = "block text-sm font-medium text-gray-200 mb-1";

    const { data, setData, post, processing, errors, reset } = useForm({
        document_type: "",
        document_number: "",
        remarks: "",
        file: null,
    });

    /*
    |--------------------------------------------------------------------------
    | PAYMENT FORM
    |--------------------------------------------------------------------------
    */

    const {
        data: paymentData,
        setData: setPaymentData,
        post: postPayment,
        processing: paymentProcessing,
        errors: paymentErrors,
        reset: resetPayment,
    } = useForm({
        amount: "",
        payment_method: "bank_transfer",
        payment_reference: "",
        payment_date: "",
        remarks: "",
        payment_proof: null,
    });

    /*
    |--------------------------------------------------------------------------
    | ROLE CHECK
    |--------------------------------------------------------------------------
    */

    const isSupplier =
        auth.user.company_id === purchaseOrder.supplier_company_id;

    const isBuyer = auth.user.id === purchaseOrder.buyer_id;
    const [responseData, setResponseData] = useState("");
    const submitResponse = (disputeId) => {
        router.post(
            route("purchase-order-disputes.respond", disputeId),
            {
                supplier_response: responseData,
            },
            {
                onSuccess: () => {
                    setResponseData("");
                },
            },
        );
    };

    /*
    |--------------------------------------------------------------------------
    | STATUS CONFIG
    |--------------------------------------------------------------------------
    */
    const paymentStatus = paymentSummary.status;
    const statusColors = {
        pending: "bg-yellow-100 text-yellow-800",
        confirmed: "bg-blue-100 text-blue-800",
        production: "bg-indigo-100 text-indigo-800",
        shipped: "bg-purple-100 text-purple-800",
        completed: "bg-green-100 text-green-800",
        cancelled: "bg-red-100 text-red-800",
    };
    const statusLabels = {
        pending: "🟡 Pending",
        confirmed: "🔵 Confirmed",
        production: "🟣 In Production",
        shipped: "🚢 Shipped",
        completed: "✅ Completed",
        cancelled: "❌ Cancelled",
    };

    const paymentProgressColor = (() => {
        switch (paymentStatus) {
            case "unpaid":
                return "bg-red-500";

            case "partial":
                return "bg-yellow-500";

            case "paid":
                return "bg-green-600";

            case "overpaid":
                return "bg-blue-600";

            default:
                return "bg-gray-400";
        }
    })();

    const documentLabels = {
        invoice: "Commercial Invoice",
        packing_list: "Packing List",
        bill_of_lading: "Bill of Lading",
        certificate_of_origin: "Certificate of Origin",
        air_waybill: "Air Waybill",
        insurance_certificate: "Insurance Certificate",
        inspection_certificate: "Inspection Certificate",
        other: "Other Document",
    };
    const documentIcons = {
        invoice: "📄",
        packing_list: "📦",
        bill_of_lading: "🚢",
        certificate_of_origin: "🌍",
        air_waybill: "✈️",
        insurance_certificate: "🛡️",
        inspection_certificate: "🔍",
        other: "📎",
    };

    const paymentMethodLabels = {
        bank_transfer: "Bank Transfer",
        letter_of_credit: "Letter of Credit",
        cash: "Cash",
        other: "Other",
    };

    /*
    |--------------------------------------------------------------------------
    | PAYMENT SUMMARY
    |--------------------------------------------------------------------------
    */

    const paymentPercentage =
        purchaseOrder.total_amount > 0
            ? (
                  (paymentSummary.total_paid / purchaseOrder.total_amount) *
                  100
              ).toFixed(0)
            : 0;

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    const formatDateTime = (date) => {
        if (!date) return "-";

        return new Date(date).toLocaleString("en-GB", {
            day: "2-digit",
            month: "short",
            year: "numeric",
            hour: "2-digit",
            minute: "2-digit",
        });
    };

    const formatDate = (date) => {
        if (!date) return "-";

        return new Date(date).toLocaleDateString("en-GB", {
            day: "2-digit",
            month: "short",
            year: "numeric",
        });
    };

    const formatCurrency = (value) =>
        Number(value || 0).toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

    const formatNumber = (value) => Number(value || 0).toLocaleString();

    /*
    |--------------------------------------------------------------------------
    | DOCUMENT SUBMIT
    |--------------------------------------------------------------------------
    */

    const submitDocument = (e) => {
        e.preventDefault();

        post(route("purchase-orders.documents.store", purchaseOrder.id), {
            forceFormData: true,

            onSuccess: () => {
                reset();
                setShowUploadForm(false);
            },
        });
    };

    /*
    |--------------------------------------------------------------------------
    | PAYMENT SUBMIT
    |--------------------------------------------------------------------------
    */

    const submitPayment = (e) => {
        e.preventDefault();

        postPayment(route("purchase-orders.payments.store", purchaseOrder.id), {
            forceFormData: true,

            onSuccess: () => {
                resetPayment();
                setShowPaymentForm(false);
            },
        });
    };
    const submitShipment = (e) => {
        e.preventDefault();

        postShipment(
            route("purchase-orders.shipment.store", purchaseOrder.id),
            {
                onSuccess: () => {
                    resetShipment();

                    setShowShipmentForm(false);
                },
            },
        );
    };

    const {
        data: shipmentData,
        setData: setShipmentData,
        post: postShipment,
        processing: shipmentProcessing,
        errors: shipmentErrors,
        reset: resetShipment,
    } = useForm({
        carrier: "",
        tracking_number: "",
        container_number: "",
        bl_number: "",
        etd: "",
        eta: "",
        current_location: "",
        remarks: "",
    });

    const [showTrackingForm, setShowTrackingForm] = useState(false);
    const {
        data: trackingData,
        setData: setTrackingData,
        post: postTracking,
        processing: trackingProcessing,
        errors: trackingErrors,
        reset: resetTracking,
    } = useForm({
        status: "picked_up",
        location: "",
        remarks: "",
        tracked_at: "",
    });
    const shipmentTrackLabels = {
        picked_up: "🚚 Picked Up",
        export_clearance: "🏗 Export Clearance",
        departed_port: "🚢 Departed Port",
        in_transit: "🌏 In Transit",
        arrived_port: "⚓ Arrived Destination Port",
        out_for_delivery: "🚛 Out For Delivery",
        delivered: "✅ Delivered",
    };
    const shipmentTrackIcons = {
        picked_up: "🚚",
        export_clearance: "🏗",
        departed_port: "🚢",
        in_transit: "🌏",
        arrived_port: "⚓",
        out_for_delivery: "🚛",
        delivered: "✅",
    };
    const shipmentTrackColors = {
        picked_up: "bg-blue-500",
        export_clearance: "bg-purple-500",
        departed_port: "bg-indigo-500",
        in_transit: "bg-cyan-500",
        arrived_port: "bg-amber-500",
        out_for_delivery: "bg-orange-500",
        delivered: "bg-green-600",
    };
    const submitTracking = (e) => {
        e.preventDefault();

        postTracking(
            route("purchase-orders.shipment-tracks.store", purchaseOrder.id),
            {
                onSuccess: () => {
                    resetTracking();

                    setShowTrackingForm(false);
                },
            },
        );
    };
    const disputeCategoryLabels = {
        quality_issue: "Quality Issue",
        quantity_shortage: "Quantity Shortage",
        damaged_goods: "Damaged Goods",
        late_delivery: "Late Delivery",
        documentation_issue: "Documentation Issue",
        other: "Other",
    };
    const disputeStatusLabels = {
        open: "🔴 Open",
        under_review: "🟡 Under Review",
        resolved: "🟢 Resolved",
        closed: "⚫ Closed",
    };
    const disputeStatusColors = {
        open: "bg-red-100 text-red-700",
        under_review: "bg-yellow-100 text-yellow-700",
        resolved: "bg-green-100 text-green-700",
        closed: "bg-gray-100 text-gray-700",
    };
    const [showDisputeForm, setShowDisputeForm] = useState(false);
    const {
        data: disputeData,
        setData: setDisputeData,
        post: postDispute,
        processing: disputeProcessing,
        reset: resetDispute,
    } = useForm({
        category: "",
        description: "",
    });

    const submitDispute = (e) => {
        e.preventDefault();

        postDispute(route("purchase-orders.disputes.store", purchaseOrder.id), {
            onSuccess: () => {
                resetDispute();

                setShowDisputeForm(false);
            },
        });
    };
    const hasActiveDispute =
        purchaseOrder.disputes?.some(
            (dispute) => dispute.status !== "closed",
        ) ?? false;
    const [showReviewForm, setShowReviewForm] = useState(false);

    const {
        data: reviewData,
        setData: setReviewData,
        post: postReview,
        processing: reviewProcessing,
        errors: reviewErrors,
        reset: resetReview,
    } = useForm({
        quality_rating: 5,
        delivery_rating: 5,
        communication_rating: 5,
        comment: "",
    });

    const submitReview = (e) => {
        e.preventDefault();

        postReview(route("purchase-orders.review.store", purchaseOrder.id), {
            onSuccess: () => {
                resetReview();

                setShowReviewForm(false);
            },
        });
    };
    return (
        <AuthenticatedLayout user={auth.user}>
            <Head title={purchaseOrder.po_number} />

            <div className="max-w-6xl mx-auto p-6 text-gray-900">
                {/* HEADER */}

                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                    <div className="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div>
                            <h1 className="text-3xl font-bold text-gray-900">
                                {purchaseOrder.po_number}
                            </h1>

                            <p className="mt-2 text-gray-600">
                                Purchase Order for{" "}
                                <span className="font-semibold text-gray-900">
                                    {purchaseOrder.rfq?.product_name}
                                </span>
                            </p>

                            <div className="flex flex-wrap gap-2 mt-4">
                                <span
                                    className={`px-3 py-1 rounded-full text-sm font-medium ${
                                        statusColors[purchaseOrder.status]
                                    }`}
                                >
                                    {statusLabels[purchaseOrder.status]}
                                </span>

                                <span
                                    className={`px-3 py-1 rounded-full text-sm font-medium ${
                                        paymentStatus === "paid"
                                            ? "bg-green-100 text-green-800"
                                            : paymentStatus === "partial"
                                              ? "bg-yellow-100 text-yellow-800"
                                              : "bg-red-100 text-red-800"
                                    }`}
                                >
                                    {paymentStatus === "paid"
                                        ? "🟢 Fully Paid"
                                        : paymentStatus === "partial"
                                          ? "🟡 Partial Payment"
                                          : "🔴 Unpaid"}
                                </span>
                            </div>
                        </div>

                        <Link
                            href={route("purchase-orders.index")}
                            className="inline-flex items-center bg-gray-100 hover:bg-gray-200 px-4 py-2 rounded-xl text-gray-700 font-medium transition"
                        >
                            ← Back
                        </Link>
                    </div>
                </div>
                {/* TRANSACTION SUMMARY */}

                {/* SUMMARY CARDS */}

                <div className="grid lg:grid-cols-3 gap-6 mb-6">
                    {/* PO VALUE */}
                    <div className="bg-white rounded-2xl shadow p-6">
                        <div className="text-sm text-gray-500 mb-2">
                            PO Value
                        </div>

                        <div className="text-2xl font-bold text-green-600">
                            {purchaseOrder.currency}{" "}
                            {formatCurrency(purchaseOrder.total_amount)}
                        </div>
                    </div>

                    {/* QUANTITY */}
                    <div className="bg-white rounded-2xl shadow p-6">
                        <div className="text-sm text-gray-500 mb-2">
                            Quantity
                        </div>

                        <div className="text-2xl font-bold text-gray-900">
                            {formatNumber(purchaseOrder.quantity)}
                        </div>

                        <div className="text-sm text-gray-500 mt-1">
                            {purchaseOrder.rfq?.unit}
                        </div>
                    </div>

                    {/* ORDER STATUS */}
                    <div className="bg-white rounded-2xl shadow p-6">
                        <div className="text-sm text-gray-500 mb-2">
                            Order Status
                        </div>

                        <span
                            className={`inline-flex px-4 py-2 rounded-full text-sm font-medium ${
                                statusColors[purchaseOrder.status]
                            }`}
                        >
                            {statusLabels[purchaseOrder.status] ||
                                purchaseOrder.status}
                        </span>
                    </div>
                </div>

                {/* FINANCIAL SUMMARY */}

                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                    <div className="flex justify-between items-center mb-6">
                        <h2 className="text-lg font-bold">Financial Summary</h2>

                        <span
                            className={`px-3 py-1 rounded-full text-sm font-medium ${
                                paymentStatus === "unpaid"
                                    ? "bg-red-100 text-red-800"
                                    : paymentStatus === "partial"
                                      ? "bg-yellow-100 text-yellow-800"
                                      : paymentStatus === "paid"
                                        ? "bg-green-100 text-green-800"
                                        : "bg-blue-100 text-blue-800"
                            }`}
                        >
                            {paymentStatus === "unpaid" && "🔴 Unpaid"}

                            {paymentStatus === "partial" &&
                                "🟡 Partial Payment"}

                            {paymentStatus === "paid" && "🟢 Fully Paid"}

                            {paymentStatus === "overpaid" && "🔵 Overpaid"}
                        </span>
                    </div>

                    <div className="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <div className="text-sm text-gray-500">
                                Paid Amount
                            </div>

                            <div className="text-2xl font-bold text-green-600 mt-1">
                                {purchaseOrder.currency}{" "}
                                {formatCurrency(paymentSummary.total_paid)}
                            </div>
                        </div>

                        <div>
                            <div className="text-sm text-gray-500">
                                Outstanding
                            </div>

                            <div className="text-2xl font-bold text-red-600 mt-1">
                                {purchaseOrder.currency}{" "}
                                {formatCurrency(paymentSummary.outstanding)}
                            </div>
                        </div>
                        {/* Tambahan */}
                        {paymentStatus === "overpaid" && (
                            <div className="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-200">
                                <div className="text-sm text-blue-600">
                                    Overpayment Amount
                                </div>

                                <div className="text-2xl font-bold text-blue-700 mt-1">
                                    {purchaseOrder.currency}{" "}
                                    {formatCurrency(
                                        paymentSummary.overpaid_amount,
                                    )}
                                </div>
                            </div>
                        )}
                    </div>

                    {/* PAYMENT PROGRESS */}

                    <div>
                        <div className="flex justify-between items-center mb-2">
                            <span className="text-sm text-gray-500">
                                Payment Progress
                            </span>

                            <span className="text-sm font-semibold">
                                {paymentSummary.percentage}%
                            </span>
                        </div>

                        <div className="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div
                                className={`${paymentProgressColor} h-3 rounded-full transition-all duration-500`}
                                style={{
                                    width: `${Math.min(
                                        paymentSummary.percentage,
                                        100,
                                    )}%`,
                                }}
                            />
                        </div>

                        <div className="text-sm text-gray-500 mt-2">
                            {purchaseOrder.currency}{" "}
                            {formatCurrency(paymentSummary.total_paid)}
                            {" / "}
                            {purchaseOrder.currency}{" "}
                            {formatCurrency(purchaseOrder.total_amount)}
                        </div>
                    </div>
                </div>

                {/* PAYMENT RECORDS */}

                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                    <div className="flex justify-between items-center">
                        <h2 className="font-bold text-lg">Payment Records</h2>

                        {isBuyer && (
                            <button
                                onClick={() =>
                                    setShowPaymentForm(!showPaymentForm)
                                }
                                className="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg"
                            >
                                Upload Payment
                            </button>
                        )}
                    </div>
                </div>
                {/* Payment bar */}

                {/* Form Payment */}
                {isBuyer && showPaymentForm && (
                    <form
                        onSubmit={submitPayment}
                        className="bg-gray-50 border rounded-xl p-5 mb-6"
                    >
                        <div className="grid md:grid-cols-2 gap-4">
                            {/* Amount */}

                            <div>
                                <label className="block text-sm font-medium mb-2">
                                    Amount
                                </label>

                                <input
                                    type="number"
                                    step="0.01"
                                    value={paymentData.amount}
                                    onChange={(e) =>
                                        setPaymentData("amount", e.target.value)
                                    }
                                    className="w-full border rounded-lg p-3"
                                    required
                                />

                                {paymentErrors.amount && (
                                    <div className="text-red-500 text-sm mt-1">
                                        {paymentErrors.amount}
                                    </div>
                                )}
                            </div>

                            {/* Payment Method */}

                            <div>
                                <label className="block text-sm font-medium mb-2">
                                    Payment Method
                                </label>

                                <select
                                    value={paymentData.payment_method}
                                    onChange={(e) =>
                                        setPaymentData(
                                            "payment_method",
                                            e.target.value,
                                        )
                                    }
                                    className="w-full border rounded-lg p-3"
                                >
                                    <option value="bank_transfer">
                                        Bank Transfer
                                    </option>

                                    <option value="letter_of_credit">
                                        Letter of Credit
                                    </option>

                                    <option value="cash">Cash</option>

                                    <option value="other">Other</option>
                                </select>
                            </div>

                            {/* Reference */}

                            <div>
                                <label className="block text-sm font-medium mb-2">
                                    Payment Reference
                                </label>

                                <input
                                    type="text"
                                    value={paymentData.payment_reference}
                                    onChange={(e) =>
                                        setPaymentData(
                                            "payment_reference",
                                            e.target.value,
                                        )
                                    }
                                    className="w-full border rounded-lg p-3"
                                />
                            </div>

                            {/* Date */}

                            <div>
                                <label className="block text-sm font-medium mb-2">
                                    Payment Date
                                </label>

                                <input
                                    type="date"
                                    value={paymentData.payment_date}
                                    onChange={(e) =>
                                        setPaymentData(
                                            "payment_date",
                                            e.target.value,
                                        )
                                    }
                                    className="w-full border rounded-lg p-3"
                                />
                            </div>

                            {/* File */}

                            <div className="md:col-span-2">
                                <label className="block text-sm font-medium mb-2">
                                    Payment Proof
                                </label>

                                <input
                                    type="file"
                                    onChange={(e) =>
                                        setPaymentData(
                                            "payment_proof",
                                            e.target.files[0],
                                        )
                                    }
                                    className="w-full border rounded-lg p-3"
                                />

                                {paymentErrors.payment_proof && (
                                    <div className="text-red-500 text-sm mt-1">
                                        {paymentErrors.payment_proof}
                                    </div>
                                )}
                            </div>

                            {/* Remarks */}

                            <div className="md:col-span-2">
                                <label className="block text-sm font-medium mb-2">
                                    Remarks
                                </label>

                                <textarea
                                    rows="3"
                                    value={paymentData.remarks}
                                    onChange={(e) =>
                                        setPaymentData(
                                            "remarks",
                                            e.target.value,
                                        )
                                    }
                                    className="w-full border rounded-lg p-3"
                                />
                            </div>
                        </div>

                        <div className="mt-4 flex gap-3">
                            <button
                                type="submit"
                                disabled={paymentProcessing}
                                className="bg-green-600 hover:bg-green-500 text-white px-5 py-3 rounded-xl"
                            >
                                {paymentProcessing
                                    ? "Uploading..."
                                    : "Save Payment"}
                            </button>

                            <button
                                type="button"
                                onClick={() => setShowPaymentForm(false)}
                                className="bg-gray-200 hover:bg-gray-300 px-5 py-3 rounded-xl"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                )}

                {/* COMMERCIAL + ORDER INFO */}

                <div className="grid lg:grid-cols-2 gap-6 mb-6">
                    {/* COMMERCIAL */}

                    <div className="bg-white rounded-2xl shadow p-6">
                        <h2 className="text-lg font-bold mb-5">
                            Commercial Information
                        </h2>

                        <div className="divide-y">
                            <div className="grid grid-cols-3 py-3">
                                <div className="text-gray-500">RFQ Number</div>

                                <div className="col-span-2">
                                    <Link
                                        href={route(
                                            "rfqs.show",
                                            purchaseOrder.rfq_id,
                                        )}
                                        className="text-blue-600 hover:underline font-medium"
                                    >
                                        {purchaseOrder.rfq?.rfq_number || "-"}
                                    </Link>
                                </div>
                            </div>

                            <div className="grid grid-cols-3 py-3">
                                <div className="text-gray-500">Buyer</div>

                                <div className="col-span-2 font-medium">
                                    {purchaseOrder.buyer?.name || "-"}
                                </div>
                            </div>

                            <div className="grid grid-cols-3 py-3">
                                <div className="text-gray-500">Supplier</div>

                                <div className="col-span-2 font-medium">
                                    {purchaseOrder.supplier?.nama_perusahaan ||
                                        "-"}
                                </div>
                            </div>

                            <div className="grid grid-cols-3 py-3">
                                <div className="text-gray-500">Product</div>

                                <div className="col-span-2 font-medium">
                                    {purchaseOrder.rfq?.product_name || "-"}
                                </div>
                            </div>

                            <div className="grid grid-cols-3 py-3">
                                <div className="text-gray-500">HS Code</div>

                                <div className="col-span-2 font-medium">
                                    {purchaseOrder.rfq?.hs_code || "-"}
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* ORDER */}

                    <div className="bg-white rounded-2xl shadow p-6">
                        <h2 className="text-lg font-bold mb-5">
                            Order Information
                        </h2>

                        <div className="divide-y">
                            <div className="grid grid-cols-3 py-3">
                                <div className="text-gray-500">Unit Price</div>

                                <div className="col-span-2 font-medium">
                                    {purchaseOrder.currency}{" "}
                                    {formatCurrency(purchaseOrder.unit_price)}
                                </div>
                            </div>

                            <div className="grid grid-cols-3 py-3">
                                <div className="text-gray-500">Quantity</div>

                                <div className="col-span-2 font-medium">
                                    {formatNumber(purchaseOrder.quantity)}{" "}
                                    {purchaseOrder.rfq?.unit}
                                </div>
                            </div>

                            <div className="grid grid-cols-3 py-3">
                                <div className="text-gray-500">
                                    Total Amount
                                </div>

                                <div className="col-span-2 text-lg font-bold text-green-600">
                                    {purchaseOrder.currency}{" "}
                                    {formatCurrency(purchaseOrder.total_amount)}
                                </div>
                            </div>

                            <div className="grid grid-cols-3 py-3">
                                <div className="text-gray-500">Incoterm</div>

                                <div className="col-span-2 font-medium">
                                    {purchaseOrder.rfq?.incoterm || "-"}
                                </div>
                            </div>

                            <div className="grid grid-cols-3 py-3">
                                <div className="text-gray-500">
                                    Delivery Date
                                </div>

                                <div className="col-span-2 font-medium">
                                    {purchaseOrder.delivery_date
                                        ? formatDate(
                                              purchaseOrder.delivery_date,
                                          )
                                        : "-"}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {/* PAYMENT MILESTONE */}

                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                    <h2 className="text-lg font-bold mb-6">
                        Payment Milestones
                    </h2>

                    <div className="space-y-4">
                        <div className="border rounded-xl p-4">
                            <div className="flex justify-between items-center">
                                <div>
                                    <div className="font-semibold">
                                        Down Payment (30%)
                                    </div>

                                    <div className="text-sm text-gray-500">
                                        Initial payment
                                    </div>
                                </div>

                                <div className="text-right">
                                    <div className="font-bold">
                                        {purchaseOrder.currency}{" "}
                                        {formatCurrency(
                                            purchaseOrder.total_amount * 0.3,
                                        )}
                                    </div>

                                    <div>
                                        {paymentSummary.total_paid >=
                                        purchaseOrder.total_amount * 0.3 ? (
                                            <span className="text-green-600 font-medium">
                                                ✓ Paid
                                            </span>
                                        ) : (
                                            <span className="text-yellow-600 font-medium">
                                                Pending
                                            </span>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="border rounded-xl p-4">
                            <div className="flex justify-between items-center">
                                <div>
                                    <div className="font-semibold">
                                        Final Payment (70%)
                                    </div>

                                    <div className="text-sm text-gray-500">
                                        Remaining balance
                                    </div>
                                </div>

                                <div className="text-right">
                                    <div className="font-bold">
                                        {purchaseOrder.currency}{" "}
                                        {formatCurrency(
                                            purchaseOrder.total_amount * 0.7,
                                        )}
                                    </div>

                                    <div>
                                        {paymentSummary.total_paid >=
                                        purchaseOrder.total_amount ? (
                                            <span className="text-green-600 font-medium">
                                                ✓ Paid
                                            </span>
                                        ) : (
                                            <span className="text-yellow-600 font-medium">
                                                Pending
                                            </span>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {/* Payment TIMELINE */}
                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                    <h2 className="text-lg font-bold mb-6">Payment Timeline</h2>

                    {purchaseOrder.payments?.length > 0 ? (
                        <div className="space-y-6">
                            {purchaseOrder.payments.map((payment, index) => (
                                <div key={payment.id} className="flex gap-4">
                                    {/* TIMELINE */}

                                    <div className="flex flex-col items-center">
                                        <div className="w-4 h-4 rounded-full bg-green-600" />

                                        {index <
                                            purchaseOrder.payments.length -
                                                1 && (
                                            <div className="w-0.5 flex-1 bg-gray-300 mt-1" />
                                        )}
                                    </div>

                                    {/* CONTENT */}

                                    <div className="flex-1 border rounded-xl p-4">
                                        <div className="flex justify-between items-start">
                                            <div>
                                                <div className="font-semibold">
                                                    Payment #{index + 1}
                                                </div>

                                                <div className="text-sm text-gray-500">
                                                    {formatDate(
                                                        payment.payment_date,
                                                    )}
                                                </div>
                                            </div>

                                            <div className="text-xl font-bold text-green-600">
                                                {purchaseOrder.currency}{" "}
                                                {formatCurrency(payment.amount)}
                                            </div>
                                        </div>

                                        <div className="mt-4 space-y-2 text-sm">
                                            <div>
                                                <span className="text-gray-500">
                                                    Method:
                                                </span>{" "}
                                                {
                                                    paymentMethodLabels[
                                                        payment.payment_method
                                                    ]
                                                }
                                            </div>

                                            {payment.payment_reference && (
                                                <div>
                                                    <span className="text-gray-500">
                                                        Reference:
                                                    </span>{" "}
                                                    {payment.payment_reference}
                                                </div>
                                            )}

                                            <div>
                                                <span className="text-gray-500">
                                                    Paid By:
                                                </span>{" "}
                                                {payment.payer?.name}
                                            </div>

                                            {payment.remarks && (
                                                <div>
                                                    <span className="text-gray-500">
                                                        Remarks:
                                                    </span>{" "}
                                                    {payment.remarks}
                                                </div>
                                            )}
                                        </div>

                                        {payment.payment_proof && (
                                            <div className="flex gap-4 mt-4">
                                                <a
                                                    href={`/storage/${payment.payment_proof}`}
                                                    target="_blank"
                                                    className="text-blue-600 hover:underline"
                                                >
                                                    View Proof
                                                </a>

                                                <a
                                                    href={`/storage/${payment.payment_proof}`}
                                                    download
                                                    className="text-blue-600 hover:underline"
                                                >
                                                    Download Proof
                                                </a>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="text-gray-500">
                            No payment records found.
                        </div>
                    )}
                </div>
                {/* SHIPMENT INFORMATION */}

                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                    <div className="flex justify-between items-center mb-6">
                        <h2 className="text-lg font-bold">
                            Shipment Information
                        </h2>

                        {isSupplier && (
                            <button
                                onClick={() => {
                                    if (purchaseOrder.shipment) {
                                        setShipmentData({
                                            carrier:
                                                purchaseOrder.shipment
                                                    .carrier || "",

                                            tracking_number:
                                                purchaseOrder.shipment
                                                    .tracking_number || "",

                                            container_number:
                                                purchaseOrder.shipment
                                                    .container_number || "",

                                            bl_number:
                                                purchaseOrder.shipment
                                                    .bl_number || "",

                                            etd:
                                                purchaseOrder.shipment.etd ||
                                                "",

                                            eta:
                                                purchaseOrder.shipment.eta ||
                                                "",

                                            current_location:
                                                purchaseOrder.shipment
                                                    .current_location || "",

                                            remarks:
                                                purchaseOrder.shipment
                                                    .remarks || "",
                                        });
                                    }

                                    setShowShipmentForm(true);
                                }}
                                className="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg"
                            >
                                Update Shipment
                            </button>
                        )}
                    </div>

                    {purchaseOrder.shipment ? (
                        <>
                            <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div className="bg-gray-50 rounded-xl p-4">
                                    <div className="text-xs uppercase tracking-wide text-slate-2300 mb-1">
                                        Carrier
                                    </div>

                                    <div className="font-semibold text-gray-900">
                                        {purchaseOrder.shipment.carrier || "-"}
                                    </div>
                                </div>

                                <div className="bg-gray-50 rounded-xl p-4">
                                    <div className="text-xs uppercase tracking-wide text-gray-500 mb-1">
                                        Tracking Number
                                    </div>

                                    <div className="font-semibold text-gray-900 break-all">
                                        {purchaseOrder.shipment
                                            .tracking_number || "-"}
                                    </div>
                                </div>

                                <div className="bg-gray-50 rounded-xl p-4">
                                    <div className="text-xs uppercase tracking-wide text-gray-500 mb-1">
                                        Container Number
                                    </div>

                                    <div className="font-semibold text-gray-900">
                                        {purchaseOrder.shipment
                                            .container_number || "-"}
                                    </div>
                                </div>

                                <div className="bg-gray-50 rounded-xl p-4">
                                    <div className="text-xs uppercase tracking-wide text-gray-500 mb-1">
                                        BL Number
                                    </div>

                                    <div className="font-semibold text-gray-900">
                                        {purchaseOrder.shipment.bl_number ||
                                            "-"}
                                    </div>
                                </div>
                            </div>

                            <div className="grid md:grid-cols-2 gap-4 mt-4">
                                <div className="bg-blue-50 border border-blue-100 rounded-xl p-4">
                                    <div className="text-xs uppercase tracking-wide text-blue-600 mb-1">
                                        ETD
                                    </div>

                                    <div className="font-semibold text-gray-900">
                                        {purchaseOrder.shipment.etd
                                            ? formatDate(
                                                  purchaseOrder.shipment.etd,
                                              )
                                            : "-"}
                                    </div>
                                </div>

                                <div className="bg-green-50 border border-green-100 rounded-xl p-4">
                                    <div className="text-xs uppercase tracking-wide text-green-600 mb-1">
                                        ETA
                                    </div>

                                    <div className="font-semibold text-gray-900">
                                        {purchaseOrder.shipment.eta
                                            ? formatDate(
                                                  purchaseOrder.shipment.eta,
                                              )
                                            : "-"}
                                    </div>
                                </div>
                            </div>

                            <div className="mt-4 bg-amber-50 border border-amber-100 rounded-xl p-4">
                                <div className="text-xs uppercase tracking-wide text-amber-700 mb-1">
                                    Current Location
                                </div>

                                <div className="font-semibold text-gray-900">
                                    {purchaseOrder.shipment.current_location ||
                                        "-"}
                                </div>
                            </div>

                            <div className="mt-4 bg-gray-50 rounded-xl p-4">
                                <div className="text-xs uppercase tracking-wide text-gray-500 mb-2">
                                    Remarks
                                </div>

                                <div className="text-gray-700 whitespace-pre-line">
                                    {purchaseOrder.shipment.remarks || "-"}
                                </div>
                            </div>
                        </>
                    ) : (
                        <div className="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-8 text-center">
                            <div className="text-4xl mb-3">🚢</div>

                            <div className="font-semibold text-gray-700">
                                No Shipment Information
                            </div>

                            <div className="text-sm text-gray-500 mt-1">
                                Shipment details have not been provided yet.
                            </div>
                        </div>
                    )}
                </div>
                {showShipmentForm && (
                    <form
                        onSubmit={submitShipment}
                        className="my-3 border-t border-gray-700 pt-6 bg-slate-800 rounded-xl p-6"
                    >
                        <div className="grid md:grid-cols-2 gap-4">
                            {/* Carrier */}

                            <div>
                                <label className={shipmentLabelClass}>
                                    Carrier
                                </label>

                                <input
                                    type="text"
                                    value={shipmentData.carrier}
                                    onChange={(e) =>
                                        setShipmentData(
                                            "carrier",
                                            e.target.value,
                                        )
                                    }
                                    className={shipmentInputClass}
                                />
                            </div>

                            {/* Tracking */}

                            <div>
                                <label className={shipmentLabelClass}>
                                    Tracking Number
                                </label>

                                <input
                                    type="text"
                                    value={shipmentData.tracking_number}
                                    onChange={(e) =>
                                        setShipmentData(
                                            "tracking_number",
                                            e.target.value,
                                        )
                                    }
                                    className={shipmentInputClass}
                                />
                            </div>

                            {/* Container */}

                            <div>
                                <label className={shipmentLabelClass}>
                                    Container Number
                                </label>

                                <input
                                    type="text"
                                    value={shipmentData.container_number}
                                    onChange={(e) =>
                                        setShipmentData(
                                            "container_number",
                                            e.target.value,
                                        )
                                    }
                                    className={shipmentInputClass}
                                />
                            </div>

                            {/* BL */}

                            <div>
                                <label className={shipmentLabelClass}>
                                    BL Number
                                </label>

                                <input
                                    type="text"
                                    value={shipmentData.bl_number}
                                    onChange={(e) =>
                                        setShipmentData(
                                            "bl_number",
                                            e.target.value,
                                        )
                                    }
                                    className={shipmentInputClass}
                                />
                            </div>

                            {/* ETD */}

                            <div>
                                <label className={shipmentLabelClass}>
                                    ETD
                                </label>

                                <input
                                    type="date"
                                    value={shipmentData.etd}
                                    onChange={(e) =>
                                        setShipmentData("etd", e.target.value)
                                    }
                                    className={shipmentInputClass}
                                />
                            </div>

                            {/* ETA */}

                            <div>
                                <label className={shipmentLabelClass}>
                                    ETA
                                </label>

                                <input
                                    type="date"
                                    value={shipmentData.eta}
                                    onChange={(e) =>
                                        setShipmentData("eta", e.target.value)
                                    }
                                    className={shipmentInputClass}
                                />
                            </div>

                            {/* Current Location */}

                            <div className="md:col-span-2">
                                <label className={shipmentLabelClass}>
                                    Current Location
                                </label>

                                <input
                                    type="text"
                                    value={shipmentData.current_location}
                                    onChange={(e) =>
                                        setShipmentData(
                                            "current_location",
                                            e.target.value,
                                        )
                                    }
                                    className={shipmentInputClass}
                                />
                            </div>

                            {/* Remarks */}

                            <div className="md:col-span-2">
                                <label className={shipmentLabelClass}>
                                    Remarks
                                </label>

                                <textarea
                                    rows="3"
                                    value={shipmentData.remarks}
                                    onChange={(e) =>
                                        setShipmentData(
                                            "remarks",
                                            e.target.value,
                                        )
                                    }
                                    className={shipmentInputClass}
                                />
                            </div>
                        </div>

                        <div className="flex gap-3 mt-6">
                            <button
                                type="submit"
                                disabled={shipmentProcessing}
                                className="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg"
                            >
                                Save Shipment
                            </button>

                            <button
                                type="button"
                                onClick={() => setShowShipmentForm(false)}
                                className="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                )}
                {/* Progress Card */}

                {/* SHIPMENT TIMELINE */}

                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                    <div className="flex justify-between items-start mb-6">
                        <div>
                            <h2 className="text-lg font-bold">
                                Shipment Journey
                            </h2>

                            <div className="text-sm text-gray-500">
                                Track shipment progress and logistics events
                            </div>
                        </div>

                        <div className="flex items-center gap-3">
                            <span className="font-semibold text-indigo-600">
                                {shipmentProgress.percentage}%
                            </span>

                            {isSupplier && (
                                <button
                                    onClick={() =>
                                        setShowTrackingForm(!showTrackingForm)
                                    }
                                    className="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg"
                                >
                                    Add Tracking Event
                                </button>
                            )}
                        </div>
                    </div>
                    {/* PROGRESS */}

                    <div className="mb-6">
                        <div className="flex justify-between items-center mb-2">
                            <span className="text-sm text-gray-500">
                                Shipment Progress
                            </span>

                            <span className="text-sm font-semibold">
                                {shipmentProgress.percentage}%
                            </span>
                        </div>

                        <div className="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                            <div
                                className="bg-indigo-600 h-4 rounded-full transition-all duration-500"
                                style={{
                                    width: `${shipmentProgress.percentage}%`,
                                }}
                            />
                        </div>

                        <div className="mt-3 bg-indigo-50 rounded-xl p-4">
                            <div className="text-sm text-gray-500">
                                Current Status
                            </div>

                            <div className="font-semibold text-gray-900 mt-1">
                                {shipmentTrackLabels[
                                    shipmentProgress.latest_status
                                ] || "Not Started"}
                            </div>
                        </div>
                    </div>
                    {showTrackingForm && (
                        <form
                            onSubmit={submitTracking}
                            className="mb-6 bg-slate-800 rounded-xl p-6"
                        >
                            <div className="grid md:grid-cols-2 gap-4">
                                {/* Status */}

                                <div>
                                    <label className="block text-sm text-slate-200 mb-2">
                                        Status
                                    </label>

                                    <select
                                        value={trackingData.status}
                                        onChange={(e) =>
                                            setTrackingData(
                                                "status",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full rounded-lg border border-slate-600 bg-slate-700 text-white px-3 py-2"
                                    >
                                        <option value="picked_up">
                                            Picked Up
                                        </option>

                                        <option value="export_clearance">
                                            Export Clearance
                                        </option>

                                        <option value="departed_port">
                                            Departed Port
                                        </option>

                                        <option value="in_transit">
                                            In Transit
                                        </option>

                                        <option value="arrived_port">
                                            Arrived Destination Port
                                        </option>

                                        <option value="out_for_delivery">
                                            Out For Delivery
                                        </option>

                                        <option value="delivered">
                                            Delivered
                                        </option>
                                    </select>
                                </div>

                                {/* Date */}

                                <div>
                                    <label className="block text-sm text-slate-200 mb-2">
                                        Tracking Date
                                    </label>

                                    <input
                                        type="date"
                                        value={trackingData.tracked_at}
                                        onChange={(e) =>
                                            setTrackingData(
                                                "tracked_at",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full rounded-lg border border-slate-600 bg-slate-700 text-white px-3 py-2"
                                    />
                                </div>

                                {/* Location */}

                                <div className="md:col-span-2">
                                    <label className="block text-sm text-slate-200 mb-2">
                                        Location
                                    </label>

                                    <input
                                        type="text"
                                        value={trackingData.location}
                                        onChange={(e) =>
                                            setTrackingData(
                                                "location",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full rounded-lg border border-slate-600 bg-slate-700 text-white px-3 py-2"
                                    />
                                </div>

                                {/* Remarks */}

                                <div className="md:col-span-2">
                                    <label className="block text-sm text-slate-200 mb-2">
                                        Remarks
                                    </label>

                                    <textarea
                                        rows="3"
                                        value={trackingData.remarks}
                                        onChange={(e) =>
                                            setTrackingData(
                                                "remarks",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full rounded-lg border border-slate-600 bg-slate-700 text-white px-3 py-2"
                                    />
                                </div>
                            </div>

                            <div className="flex gap-3 mt-6">
                                <button
                                    type="submit"
                                    disabled={trackingProcessing}
                                    className="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg"
                                >
                                    Save Tracking
                                </button>

                                <button
                                    type="button"
                                    onClick={() => setShowTrackingForm(false)}
                                    className="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    )}
                    <div className="border-t pt-6">
                        <h3 className="font-semibold text-gray-900 mb-4">
                            Tracking Timeline
                        </h3>
                    </div>
                    {purchaseOrder.shipment?.tracks?.length > 0 ? (
                        <div className="space-y-6">
                            {[...purchaseOrder.shipment.tracks]
                                .sort((a, b) => {
                                    const dateDiff =
                                        new Date(b.tracked_at) -
                                        new Date(a.tracked_at);

                                    if (dateDiff !== 0) {
                                        return dateDiff;
                                    }

                                    return b.id - a.id;
                                })
                                .map((track, index, tracks) => (
                                    <div
                                        key={track.id}
                                        className="relative pl-14"
                                    >
                                        {/* LINE */}

                                        {index !== tracks.length - 1 && (
                                            <div className="absolute left-6 top-10 bottom-0 w-0.5 bg-gray-300" />
                                        )}

                                        {/* ICON */}

                                        <div
                                            className={`absolute left-0 top-0 w-12 h-12 rounded-full flex items-center justify-center text-white text-lg shadow ${
                                                shipmentTrackColors[
                                                    track.status
                                                ] || "bg-gray-500"
                                            }`}
                                        >
                                            {shipmentTrackIcons[track.status] ||
                                                "📦"}
                                        </div>

                                        {/* EVENT CARD */}

                                        <div
                                            className={`rounded-xl border p-4 ${
                                                index === 0
                                                    ? "border-indigo-300 bg-indigo-50"
                                                    : "border-gray-200 bg-white"
                                            }`}
                                        >
                                            <div className="flex justify-between items-start">
                                                <div>
                                                    <div className="font-semibold text-gray-900">
                                                        {shipmentTrackLabels[
                                                            track.status
                                                        ] || track.status}
                                                    </div>

                                                    <div className="text-sm text-gray-500 mt-1">
                                                        {track.location || "-"}
                                                    </div>
                                                </div>

                                                {index === 0 && (
                                                    <span className="text-xs px-2 py-1 rounded-full bg-indigo-100 text-indigo-700">
                                                        Latest
                                                    </span>
                                                )}
                                            </div>

                                            {track.remarks && (
                                                <div className="text-sm text-gray-700 mt-3">
                                                    {track.remarks}
                                                </div>
                                            )}

                                            <div className="text-xs text-gray-400 mt-3">
                                                {formatDateTime(
                                                    track.tracked_at,
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                ))}
                        </div>
                    ) : (
                        <div className="bg-gray-50 border border-dashed border-gray-300 rounded-xl p-8 text-center">
                            <div className="text-4xl mb-3">📦</div>

                            <div className="font-semibold text-gray-700">
                                No Tracking Events
                            </div>

                            <div className="text-sm text-gray-500 mt-1">
                                Shipment tracking has not been updated yet.
                            </div>
                        </div>
                    )}
                </div>

                {/* Konfirmasi penerimaan Barang */}
                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                    <h2 className="text-lg font-bold mb-4">
                        Goods Receipt Confirmation
                    </h2>

                    {goodsReceived.received_at ? (
                        <div className="bg-green-50 border border-green-200 rounded-xl p-4">
                            <div className="text-green-700 font-semibold">
                                ✅ Goods Received
                            </div>

                            <div className="text-sm text-gray-600 mt-1">
                                Confirmed on{" "}
                                {formatDateTime(goodsReceived.received_at)}
                            </div>
                        </div>
                    ) : (
                        <div>
                            <div className="text-gray-500 mb-4">
                                Buyer has not confirmed receipt yet.
                            </div>

                            {isBuyer &&
                                purchaseOrder.status === "completed" && (
                                    <button
                                        onClick={() =>
                                            router.post(
                                                route(
                                                    "purchase-orders.confirm-received",
                                                    purchaseOrder.id,
                                                ),
                                            )
                                        }
                                        className="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg"
                                    >
                                        Confirm Goods Received
                                    </button>
                                )}
                        </div>
                    )}
                </div>

                {/* Dispute */}
                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                    {/* HEADER */}

                    <div className="flex justify-between items-center">
                        <div>
                            <h2 className="text-lg font-bold">
                                Dispute & Claim Center
                            </h2>

                            <div className="text-sm text-gray-500 mt-1">
                                Manage trade disputes and claims
                            </div>
                        </div>

                        {isBuyer &&
                            purchaseOrder.goods_received_at &&
                            !hasActiveDispute && (
                                <button
                                    onClick={() =>
                                        setShowDisputeForm(!showDisputeForm)
                                    }
                                    className="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded-lg"
                                >
                                    Raise Dispute
                                </button>
                            )}
                    </div>

                    {/* DISPUTE FORM */}

                    {showDisputeForm && (
                        <form
                            onSubmit={submitDispute}
                            className="mt-6 border rounded-xl p-5 bg-red-50"
                        >
                            <div className="grid gap-4">
                                <div>
                                    <label className="block text-sm font-medium mb-2">
                                        Category
                                    </label>

                                    <select
                                        value={disputeData.category}
                                        onChange={(e) =>
                                            setDisputeData(
                                                "category",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full border rounded-lg p-3"
                                        required
                                    >
                                        <option value="">
                                            Select Category
                                        </option>

                                        <option value="quality_issue">
                                            Quality Issue
                                        </option>

                                        <option value="quantity_shortage">
                                            Quantity Shortage
                                        </option>

                                        <option value="damaged_goods">
                                            Damaged Goods
                                        </option>

                                        <option value="late_delivery">
                                            Late Delivery
                                        </option>

                                        <option value="documentation_issue">
                                            Documentation Issue
                                        </option>

                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div>
                                    <label className="block text-sm font-medium mb-2">
                                        Description
                                    </label>

                                    <textarea
                                        rows="5"
                                        value={disputeData.description}
                                        onChange={(e) =>
                                            setDisputeData(
                                                "description",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full border rounded-lg p-3"
                                        required
                                    />
                                </div>
                            </div>

                            <div className="mt-4 flex gap-3">
                                <button
                                    type="submit"
                                    disabled={disputeProcessing}
                                    className="bg-red-600 hover:bg-red-500 text-white px-5 py-3 rounded-xl"
                                >
                                    Submit Dispute
                                </button>

                                <button
                                    type="button"
                                    onClick={() => setShowDisputeForm(false)}
                                    className="bg-gray-200 hover:bg-gray-300 px-5 py-3 rounded-xl"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    )}

                    {/* DISPUTE LIST */}

                    {purchaseOrder.disputes?.length > 0 ? (
                        <div className="mt-6 space-y-4">
                            {purchaseOrder.disputes.map((dispute) => (
                                <div
                                    key={dispute.id}
                                    className={`rounded-xl p-5 border ${
                                        dispute.status === "resolved"
                                            ? "bg-green-50 border-green-200"
                                            : dispute.status === "under_review"
                                              ? "bg-yellow-50 border-yellow-200"
                                              : dispute.status === "closed"
                                                ? "bg-gray-50 border-gray-200"
                                                : "bg-red-50 border-red-200"
                                    }`}
                                >
                                    <div className="flex justify-between items-start">
                                        <div>
                                            <div
                                                className={`font-semibold ${
                                                    dispute.status ===
                                                    "resolved"
                                                        ? "text-green-700"
                                                        : dispute.status ===
                                                            "under_review"
                                                          ? "text-yellow-700"
                                                          : dispute.status ===
                                                              "closed"
                                                            ? "text-gray-700"
                                                            : "text-red-700"
                                                }`}
                                            >
                                                {dispute.dispute_number}
                                            </div>

                                            <div className="text-sm text-gray-600 mt-1">
                                                {
                                                    disputeCategoryLabels[
                                                        dispute.category
                                                    ]
                                                }
                                            </div>
                                        </div>

                                        <span
                                            className={`px-3 py-1 rounded-full text-xs font-medium ${
                                                disputeStatusColors[
                                                    dispute.status
                                                ]
                                            }`}
                                        >
                                            {
                                                disputeStatusLabels[
                                                    dispute.status
                                                ]
                                            }
                                        </span>
                                    </div>

                                    <div className="mt-4 text-gray-700">
                                        {dispute.description}
                                    </div>

                                    <div className="mt-4 text-xs text-gray-500">
                                        Created:{" "}
                                        {formatDateTime(dispute.created_at)}
                                    </div>

                                    {/* SUPPLIER RESPONSE DISPLAY */}
                                    {dispute.supplier_response && (
                                        <div className="mt-4 border-t pt-4">
                                            <div className="text-sm font-semibold text-gray-700 mb-2">
                                                Supplier Response
                                            </div>

                                            <div
                                                className={`rounded-lg p-3 text-sm text-gray-700 border ${
                                                    dispute.status ===
                                                    "resolved"
                                                        ? "bg-green-50 border-green-200"
                                                        : dispute.status ===
                                                            "under_review"
                                                          ? "bg-yellow-50 border-yellow-200"
                                                          : "bg-blue-50 border-blue-100"
                                                }`}
                                            >
                                                {dispute.supplier_response}
                                            </div>

                                            {dispute.reviewed_at && (
                                                <div className="text-xs text-gray-500 mt-2">
                                                    Reviewed:{" "}
                                                    {formatDateTime(
                                                        dispute.reviewed_at,
                                                    )}
                                                </div>
                                            )}

                                            {dispute.resolved_at && (
                                                <div className="text-xs text-green-700 mt-2">
                                                    Resolved:{" "}
                                                    {formatDateTime(
                                                        dispute.resolved_at,
                                                    )}
                                                </div>
                                            )}

                                            {dispute.closed_at && (
                                                <div className="text-xs text-gray-700 mt-2">
                                                    Closed:{" "}
                                                    {formatDateTime(
                                                        dispute.closed_at,
                                                    )}
                                                </div>
                                            )}
                                        </div>
                                    )}

                                    {/* SUPPLIER RESPONSE FORM */}
                                    {isSupplier &&
                                        dispute.status === "open" &&
                                        !dispute.supplier_response && (
                                            <div className="mt-4 border-t pt-4">
                                                <div className="text-sm font-semibold mb-2">
                                                    Submit Response
                                                </div>

                                                <textarea
                                                    rows="4"
                                                    value={responseData}
                                                    onChange={(e) =>
                                                        setResponseData(
                                                            e.target.value,
                                                        )
                                                    }
                                                    className="w-full border rounded-lg p-3"
                                                    placeholder="Write supplier response..."
                                                />

                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        submitResponse(
                                                            dispute.id,
                                                        )
                                                    }
                                                    className="mt-3 bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg"
                                                >
                                                    Submit Response
                                                </button>
                                            </div>
                                        )}
                                    {/* BUYER RESOLVE BUTTON */}
                                    {isBuyer &&
                                        dispute.status === "under_review" &&
                                        dispute.supplier_response && (
                                            <div className="mt-4 border-t pt-4">
                                                <button
                                                    type="button"
                                                    onClick={() => {
                                                        if (
                                                            confirm(
                                                                "Are you sure this dispute has been resolved?",
                                                            )
                                                        ) {
                                                            router.post(
                                                                route(
                                                                    "purchase-order-disputes.resolve",
                                                                    dispute.id,
                                                                ),
                                                            );
                                                        }
                                                    }}
                                                    className="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg"
                                                >
                                                    Resolve Dispute
                                                </button>
                                            </div>
                                        )}

                                    {isBuyer &&
                                        dispute.status === "resolved" && (
                                            <div className="mt-4 border-t pt-4">
                                                <button
                                                    type="button"
                                                    onClick={() => {
                                                        if (
                                                            confirm(
                                                                "Close this dispute permanently?",
                                                            )
                                                        ) {
                                                            router.post(
                                                                route(
                                                                    "purchase-order-disputes.close",
                                                                    dispute.id,
                                                                ),
                                                            );
                                                        }
                                                    }}
                                                    className="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg"
                                                >
                                                    Close Dispute
                                                </button>
                                            </div>
                                        )}
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="mt-6 bg-gray-50 border border-dashed border-gray-300 rounded-xl p-8 text-center">
                            <div className="text-4xl mb-3">⚠️</div>

                            <div className="font-semibold text-gray-700">
                                No Disputes
                            </div>

                            <div className="text-sm text-gray-500 mt-1">
                                No claims have been submitted.
                            </div>
                        </div>
                    )}
                </div>
                {/* Rating */}
                {isBuyer && purchaseOrder.status === "completed" && (
                    <div className="bg-white rounded-2xl shadow p-6 mb-6">
                        <div className="flex justify-between items-center mb-4">
                            <h2 className="text-lg font-bold">
                                Supplier Review
                            </h2>

                            {!purchaseOrder.review && (
                                <button
                                    onClick={() =>
                                        setShowReviewForm(!showReviewForm)
                                    }
                                    className="bg-yellow-600 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg"
                                >
                                    Rate Supplier
                                </button>
                            )}
                        </div>

                        {/* Existing Review */}

                        {purchaseOrder.review && (
                            <div className="space-y-3">
                                <div>
                                    Quality: ⭐{" "}
                                    {purchaseOrder.review.quality_rating}/5
                                </div>

                                <div>
                                    Delivery: ⭐{" "}
                                    {purchaseOrder.review.delivery_rating}/5
                                </div>

                                <div>
                                    Communication: ⭐{" "}
                                    {purchaseOrder.review.communication_rating}
                                    /5
                                </div>

                                <div className="font-bold text-lg text-green-600">
                                    Overall:{" "}
                                    {purchaseOrder.review.overall_rating}/5
                                </div>

                                {purchaseOrder.review.comment && (
                                    <div className="bg-gray-50 rounded-lg p-3">
                                        {purchaseOrder.review.comment}
                                    </div>
                                )}
                            </div>
                        )}

                        {/* Review Form */}

                        {!purchaseOrder.review && showReviewForm && (
                            <form onSubmit={submitReview} className="space-y-4">
                                <div>
                                    <label>Quality</label>

                                    <select
                                        value={reviewData.quality_rating}
                                        onChange={(e) =>
                                            setReviewData(
                                                "quality_rating",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full border rounded-lg p-3"
                                    >
                                        {[1, 2, 3, 4, 5].map((n) => (
                                            <option key={n} value={n}>
                                                {n} Star
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div>
                                    <label>Delivery</label>

                                    <select
                                        value={reviewData.delivery_rating}
                                        onChange={(e) =>
                                            setReviewData(
                                                "delivery_rating",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full border rounded-lg p-3"
                                    >
                                        {[1, 2, 3, 4, 5].map((n) => (
                                            <option key={n} value={n}>
                                                {n} Star
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div>
                                    <label>Communication</label>

                                    <select
                                        value={reviewData.communication_rating}
                                        onChange={(e) =>
                                            setReviewData(
                                                "communication_rating",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full border rounded-lg p-3"
                                    >
                                        {[1, 2, 3, 4, 5].map((n) => (
                                            <option key={n} value={n}>
                                                {n} Star
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div>
                                    <label>Comment</label>

                                    <textarea
                                        rows="4"
                                        value={reviewData.comment}
                                        onChange={(e) =>
                                            setReviewData(
                                                "comment",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full border rounded-lg p-3"
                                    />
                                </div>

                                <div className="flex gap-3">
                                    <button
                                        type="submit"
                                        disabled={reviewProcessing}
                                        className="bg-green-600 hover:bg-green-500 text-white px-5 py-3 rounded-xl"
                                    >
                                        Submit Review
                                    </button>

                                    <button
                                        type="button"
                                        onClick={() => setShowReviewForm(false)}
                                        className="bg-gray-200 px-5 py-3 rounded-xl"
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        )}
                    </div>
                )}
                {/* ORDER TIMELINE */}

                <div className="bg-white rounded-2xl shadow p-6 mb-6">
                    <h2 className="font-bold text-lg mb-5">Order Timeline</h2>

                    {/* CURRENT STAGE */}

                    <div className="mb-6 pb-6 border-b">
                        {purchaseOrder.status === "pending" && (
                            <>
                                <div className="font-semibold text-yellow-700">
                                    🟡 Pending Confirmation
                                </div>

                                <div className="text-sm text-gray-500 mt-1">
                                    Waiting supplier confirmation before
                                    production can begin.
                                </div>
                            </>
                        )}

                        {purchaseOrder.status === "confirmed" && (
                            <>
                                <div className="font-semibold text-blue-700">
                                    🔵 Order Confirmed
                                </div>

                                <div className="text-sm text-gray-500 mt-1">
                                    Production can now begin.
                                </div>

                                {isSupplier && (
                                    <button
                                        onClick={() =>
                                            router.post(
                                                route(
                                                    "purchase-orders.production",
                                                    purchaseOrder.id,
                                                ),
                                            )
                                        }
                                        className="mt-4 bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-3 rounded-xl"
                                    >
                                        Start Production
                                    </button>
                                )}
                            </>
                        )}

                        {purchaseOrder.status === "production" && (
                            <>
                                <div className="font-semibold text-indigo-700">
                                    🟣 In Production
                                </div>

                                <div className="text-sm text-gray-500 mt-1">
                                    Manufacturing is currently in progress.
                                </div>

                                {isSupplier && (
                                    <button
                                        onClick={() =>
                                            router.post(
                                                route(
                                                    "purchase-orders.shipped",
                                                    purchaseOrder.id,
                                                ),
                                            )
                                        }
                                        className="mt-4 bg-purple-600 hover:bg-purple-500 text-white px-5 py-3 rounded-xl"
                                    >
                                        Mark as Shipped
                                    </button>
                                )}
                            </>
                        )}

                        {purchaseOrder.status === "shipped" && (
                            <>
                                <div className="font-semibold text-purple-700">
                                    🚢 Goods Shipped
                                </div>

                                <div className="text-sm text-gray-500 mt-1">
                                    Shipment is on the way to buyer.
                                </div>

                                {isBuyer && (
                                    <button
                                        onClick={() =>
                                            router.post(
                                                route(
                                                    "purchase-orders.completed",
                                                    purchaseOrder.id,
                                                ),
                                            )
                                        }
                                        className="mt-4 bg-green-600 hover:bg-green-500 text-white px-5 py-3 rounded-xl"
                                    >
                                        Complete Order
                                    </button>
                                )}
                            </>
                        )}

                        {purchaseOrder.status === "completed" && (
                            <>
                                <div className="font-semibold text-green-700">
                                    ✅ Transaction Completed
                                </div>

                                <div className="text-sm text-gray-500 mt-1">
                                    Buyer has received goods and the transaction
                                    has been successfully completed.
                                </div>
                            </>
                        )}
                    </div>

                    {/* TIMELINE HISTORY */}

                    <div className="space-y-5 border-l-2 border-gray-200 pl-5">
                        <div>
                            <div className="font-medium text-green-600">
                                ✓ Order Created
                            </div>

                            <div className="text-sm text-gray-500">
                                {formatDateTime(purchaseOrder.created_at)}
                            </div>
                        </div>

                        {purchaseOrder.confirmed_at && (
                            <div>
                                <div className="font-medium text-blue-600">
                                    ✓ Confirmed
                                </div>

                                <div className="text-sm text-gray-500">
                                    {formatDateTime(purchaseOrder.confirmed_at)}
                                </div>
                            </div>
                        )}

                        {purchaseOrder.production_started_at && (
                            <div>
                                <div className="font-medium text-indigo-600">
                                    ✓ Production Started
                                </div>

                                <div className="text-sm text-gray-500">
                                    {formatDateTime(
                                        purchaseOrder.production_started_at,
                                    )}
                                </div>
                            </div>
                        )}

                        {purchaseOrder.shipped_at && (
                            <div>
                                <div className="font-medium text-purple-600">
                                    ✓ Shipped
                                </div>

                                <div className="text-sm text-gray-500">
                                    {formatDateTime(purchaseOrder.shipped_at)}
                                </div>
                            </div>
                        )}

                        {purchaseOrder.completed_at && (
                            <div>
                                <div className="font-medium text-green-700">
                                    ✓ Completed
                                </div>

                                <div className="text-sm text-gray-500">
                                    {formatDateTime(purchaseOrder.completed_at)}
                                </div>
                            </div>
                        )}
                    </div>
                </div>

                {/* DOCUMENTS */}

                <div className="bg-white rounded-2xl shadow p-6">
                    <div className="flex justify-between items-center mb-4">
                        <h2 className="text-lg font-semibold">
                            Documents & Attachments
                        </h2>

                        {isSupplier && (
                            <button
                                type="button"
                                onClick={() =>
                                    setShowUploadForm(!showUploadForm)
                                }
                                className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg"
                            >
                                Upload Document
                            </button>
                        )}
                    </div>

                    {purchaseOrder.documents?.length > 0 ? (
                        <div className="space-y-4">
                            {purchaseOrder.documents.map((doc) => (
                                <div
                                    key={doc.id}
                                    className="border rounded-xl p-4 bg-gray-50"
                                >
                                    <div className="flex justify-between items-start">
                                        <div>
                                            <div className="font-semibold text-gray-900">
                                                {doc.document_type
                                                    .replaceAll("_", " ")
                                                    .replace(/\b\w/g, (c) =>
                                                        c.toUpperCase(),
                                                    )}
                                            </div>

                                            <div className="text-sm text-gray-500">
                                                Document No:{" "}
                                                {doc.document_number || "-"}
                                            </div>

                                            <div className="text-sm text-gray-500">
                                                Uploaded by:{" "}
                                                {doc.uploader?.company
                                                    ?.nama_perusahaan ??
                                                    doc.uploader?.name}
                                            </div>

                                            <div className="text-sm text-gray-500">
                                                {new Date(
                                                    doc.created_at,
                                                ).toLocaleString()}
                                            </div>
                                        </div>

                                        <div className="flex gap-4">
                                            <a
                                                href={`/storage/${doc.file_path}`}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="text-blue-600"
                                            >
                                                View
                                            </a>

                                            <a
                                                href={`/storage/${doc.file_path}`}
                                                download
                                                className="text-green-600"
                                            >
                                                Download
                                            </a>
                                        </div>
                                    </div>

                                    {doc.remarks && (
                                        <div className="mt-2 text-sm text-gray-600">
                                            <strong>Remarks:</strong>{" "}
                                            {doc.remarks}
                                        </div>
                                    )}
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="text-gray-500">
                            No documents uploaded.
                        </div>
                    )}

                    {isSupplier && showUploadForm && (
                        <form
                            onSubmit={submitDocument}
                            className="border rounded-xl p-5 bg-gray-50 mb-4"
                        >
                            <div className="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium mb-2">
                                        Document Type
                                    </label>

                                    <select
                                        value={data.document_type}
                                        onChange={(e) =>
                                            setData(
                                                "document_type",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full border rounded-lg p-3"
                                        required
                                    >
                                        <option value="">
                                            Select Document
                                        </option>

                                        <option value="invoice">
                                            Commercial Invoice
                                        </option>

                                        <option value="packing_list">
                                            Packing List
                                        </option>

                                        <option value="bill_of_lading">
                                            Bill of Lading
                                        </option>

                                        <option value="certificate_of_origin">
                                            Certificate of Origin (COO)
                                        </option>

                                        <option value="air_waybill">
                                            Air Waybill
                                        </option>

                                        <option value="insurance_certificate">
                                            Insurance Certificate
                                        </option>

                                        <option value="inspection_certificate">
                                            Inspection Certificate
                                        </option>

                                        <option value="other">Other</option>
                                    </select>

                                    {errors.document_type && (
                                        <div className="text-red-500 text-sm mt-1">
                                            {errors.document_type}
                                        </div>
                                    )}
                                </div>

                                <div>
                                    <label className="block text-sm font-medium mb-2">
                                        Document Number
                                    </label>

                                    <input
                                        type="text"
                                        value={data.document_number}
                                        onChange={(e) =>
                                            setData(
                                                "document_number",
                                                e.target.value,
                                            )
                                        }
                                        className="w-full border rounded-lg p-3"
                                    />
                                </div>

                                <div className="md:col-span-2">
                                    <label className="block text-sm font-medium mb-2">
                                        File
                                    </label>

                                    <input
                                        type="file"
                                        onChange={(e) =>
                                            setData("file", e.target.files[0])
                                        }
                                        className="w-full border rounded-lg p-3"
                                        required
                                    />

                                    {errors.file && (
                                        <div className="text-red-500 text-sm mt-1">
                                            {errors.file}
                                        </div>
                                    )}
                                </div>

                                <div className="md:col-span-2">
                                    <label className="block text-sm font-medium mb-2">
                                        Remarks
                                    </label>

                                    <textarea
                                        rows="3"
                                        value={data.remarks}
                                        onChange={(e) =>
                                            setData("remarks", e.target.value)
                                        }
                                        className="w-full border rounded-lg p-3"
                                    />
                                </div>
                            </div>
                            {isSupplier && (
                                <div className="mt-4 flex gap-3">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="bg-blue-600 hover:bg-blue-500 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-5 py-3 rounded-xl transition"
                                    >
                                        {processing
                                            ? "Uploading..."
                                            : "Upload Document"}
                                    </button>

                                    <button
                                        type="button"
                                        onClick={() => setShowUploadForm(false)}
                                        className="bg-gray-200 hover:bg-gray-300 px-5 py-3 rounded-xl transition"
                                    >
                                        Cancel
                                    </button>
                                </div>
                            )}
                        </form>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
