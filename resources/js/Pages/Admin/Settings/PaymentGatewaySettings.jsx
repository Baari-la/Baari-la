import AdminLayout from "@/Layouts/AdminLayout";
import { Head } from "@inertiajs/react";

export default function PaymentGatewaySettings({ settings = {} }) {
    const gateways = [
        "QRIS",
        "Manual Transfer",
        "Midtrans",
        "Xendit",
        "Stripe",
        "PayPal",
    ];

    return (
        <AdminLayout>
            <Head title="Payment Gateway Settings" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        Payment Gateway Settings
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Configure payment providers used across Memberships,
                        Digital Directory, and DIGESTEX services.
                    </p>
                </div>

                {/* Gateway Overview */}

                <div className="grid gap-6 md:grid-cols-3">
                    {gateways.map((gateway) => (
                        <div
                            key={gateway}
                            className="
                                rounded-3xl
                                bg-white
                                p-6
                                shadow-sm
                            "
                        >
                            <h2 className="text-lg font-bold">{gateway}</h2>

                            <div className="mt-4">
                                <span
                                    className="
                                        rounded-full
                                        bg-emerald-100
                                        px-3
                                        py-1
                                        text-sm
                                        font-semibold
                                        text-emerald-700
                                    "
                                >
                                    Enabled
                                </span>
                            </div>
                        </div>
                    ))}
                </div>

                {/* QRIS */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">
                        QRIS Configuration
                    </h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <div>
                            <label className="font-semibold">
                                Merchant Name
                            </label>

                            <input
                                type="text"
                                defaultValue={settings.qris_name ?? "DIGESTEX"}
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            />
                        </div>

                        <div>
                            <label className="font-semibold">Merchant ID</label>

                            <input
                                type="text"
                                defaultValue={settings.qris_merchant_id}
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            />
                        </div>
                    </div>
                </div>

                {/* Midtrans */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Midtrans</h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <input
                            type="text"
                            placeholder="Server Key"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />

                        <input
                            type="text"
                            placeholder="Client Key"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />
                    </div>
                </div>

                {/* Xendit */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Xendit</h2>

                    <input
                        type="text"
                        placeholder="Xendit Secret Key"
                        className="
                            w-full
                            rounded-2xl
                            border
                            p-3
                        "
                    />
                </div>

                {/* Stripe */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Stripe</h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <input
                            type="text"
                            placeholder="Publishable Key"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />

                        <input
                            type="text"
                            placeholder="Secret Key"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />
                    </div>
                </div>

                {/* PayPal */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">PayPal</h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <input
                            type="text"
                            placeholder="Client ID"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />

                        <input
                            type="text"
                            placeholder="Secret"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />
                    </div>
                </div>

                {/* Manual Transfer */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Manual Transfer</h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <input
                            type="text"
                            placeholder="Bank Name"
                            defaultValue="Bank Mandiri"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />

                        <input
                            type="text"
                            placeholder="Account Number"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />

                        <input
                            type="text"
                            placeholder="Account Holder"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />
                    </div>
                </div>

                {/* Environment */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Environment</h2>

                    <div className="grid gap-6 md:grid-cols-3">
                        <div>
                            <label className="font-semibold">Mode</label>

                            <select
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                                defaultValue="sandbox"
                            >
                                <option>sandbox</option>

                                <option>production</option>
                            </select>
                        </div>

                        <div>
                            <label className="font-semibold">
                                Default Currency
                            </label>

                            <select
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                                defaultValue="IDR"
                            >
                                <option>IDR</option>
                                <option>USD</option>
                            </select>
                        </div>

                        <div>
                            <label className="font-semibold">
                                Default Gateway
                            </label>

                            <select
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                                defaultValue="QRIS"
                            >
                                <option>QRIS</option>
                                <option>Midtrans</option>
                                <option>Xendit</option>
                                <option>Stripe</option>
                                <option>PayPal</option>
                            </select>
                        </div>
                    </div>

                    <button
                        className="
                            mt-8
                            rounded-2xl
                            bg-emerald-600
                            px-6
                            py-3
                            font-semibold
                            text-white
                        "
                    >
                        Save Payment Settings
                    </button>
                </div>
            </div>
        </AdminLayout>
    );
}
