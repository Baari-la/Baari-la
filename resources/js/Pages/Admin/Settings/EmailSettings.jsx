import AdminLayout from "@/Layouts/AdminLayout";
import { Head } from "@inertiajs/react";

export default function EmailSettings({ settings = {} }) {
    return (
        <AdminLayout>
            <Head title="Email Settings" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        Email Settings
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Configure SMTP providers, sender identity, notification
                        settings, and email delivery.
                    </p>
                </div>

                {/* Provider */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Email Provider</h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <div>
                            <label className="font-semibold">Mail Driver</label>

                            <select
                                defaultValue={settings.mail_driver ?? "smtp"}
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            >
                                <option value="smtp">SMTP</option>

                                <option value="resend">Resend</option>

                                <option value="mailgun">Mailgun</option>

                                <option value="ses">Amazon SES</option>

                                <option value="log">Log</option>
                            </select>
                        </div>

                        <div>
                            <label className="font-semibold">Environment</label>

                            <select
                                defaultValue="production"
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            >
                                <option>production</option>

                                <option>sandbox</option>
                            </select>
                        </div>
                    </div>
                </div>

                {/* SMTP */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">
                        SMTP Configuration
                    </h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <div>
                            <label className="font-semibold">SMTP Host</label>

                            <input
                                type="text"
                                defaultValue={
                                    settings.mail_host ?? "smtp.gmail.com"
                                }
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
                            <label className="font-semibold">SMTP Port</label>

                            <input
                                type="text"
                                defaultValue={settings.mail_port ?? "587"}
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
                            <label className="font-semibold">Username</label>

                            <input
                                type="text"
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
                            <label className="font-semibold">Password</label>

                            <input
                                type="password"
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
                            <label className="font-semibold">Encryption</label>

                            <select
                                defaultValue="tls"
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            >
                                <option>tls</option>

                                <option>ssl</option>

                                <option>none</option>
                            </select>
                        </div>
                    </div>
                </div>

                {/* Sender */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Sender Identity</h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <div>
                            <label className="font-semibold">From Name</label>

                            <input
                                type="text"
                                defaultValue={
                                    settings.mail_from_name ?? "DIGESTEX"
                                }
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
                            <label className="font-semibold">From Email</label>

                            <input
                                type="email"
                                defaultValue={
                                    settings.mail_from_address ??
                                    "noreply@digestex.com"
                                }
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

                {/* Notifications */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">
                        Notification Settings
                    </h2>

                    <div className="space-y-4">
                        <label className="flex items-center gap-3">
                            <input type="checkbox" defaultChecked />
                            User Registration
                        </label>

                        <label className="flex items-center gap-3">
                            <input type="checkbox" defaultChecked />
                            Email Verification
                        </label>

                        <label className="flex items-center gap-3">
                            <input type="checkbox" defaultChecked />
                            Payment Confirmation
                        </label>

                        <label className="flex items-center gap-3">
                            <input type="checkbox" defaultChecked />
                            Invoice Delivery
                        </label>

                        <label className="flex items-center gap-3">
                            <input type="checkbox" defaultChecked />
                            Membership Approval
                        </label>

                        <label className="flex items-center gap-3">
                            <input type="checkbox" defaultChecked />
                            Digital Directory Notifications
                        </label>
                    </div>
                </div>

                {/* Test Email */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Test Email</h2>

                    <div className="flex gap-4">
                        <input
                            type="email"
                            placeholder="Enter email address"
                            className="
                                flex-1
                                rounded-2xl
                                border
                                p-3
                            "
                        />

                        <button
                            className="
                                rounded-2xl
                                bg-blue-600
                                px-6
                                py-3
                                font-semibold
                                text-white
                            "
                        >
                            Send Test
                        </button>
                    </div>
                </div>

                {/* Save */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <button
                        className="
                            rounded-2xl
                            bg-emerald-600
                            px-8
                            py-3
                            font-semibold
                            text-white
                        "
                    >
                        Save Email Settings
                    </button>
                </div>
            </div>
        </AdminLayout>
    );
}
