import AdminLayout from "@/Layouts/AdminLayout";
import { Head } from "@inertiajs/react";

export default function GeneralSettings({ settings = {} }) {
    return (
        <AdminLayout>
            <Head title="General Settings" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        General Settings
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Configure the core identity and platform settings of
                        DIGESTEX.
                    </p>
                </div>

                {/* Platform Information */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">
                        Platform Information
                    </h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <div>
                            <label className="text-sm font-semibold">
                                Platform Name
                            </label>

                            <input
                                type="text"
                                defaultValue={
                                    settings.platform_name ?? "DIGESTEX"
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
                            <label className="text-sm font-semibold">
                                Tagline
                            </label>

                            <input
                                type="text"
                                defaultValue={
                                    settings.tagline ??
                                    "Where Textile Meets Intelligence"
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
                            <label className="text-sm font-semibold">
                                Support Email
                            </label>

                            <input
                                type="email"
                                defaultValue={
                                    settings.support_email ??
                                    "support@digestex.com"
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
                            <label className="text-sm font-semibold">
                                Contact Number
                            </label>

                            <input
                                type="text"
                                defaultValue={settings.phone ?? "+62"}
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

                {/* Localization */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Localization</h2>

                    <div className="grid gap-6 md:grid-cols-3">
                        <div>
                            <label className="text-sm font-semibold">
                                Timezone
                            </label>

                            <select
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                                defaultValue={
                                    settings.timezone ?? "Asia/Jakarta"
                                }
                            >
                                <option>Asia/Jakarta</option>

                                <option>UTC</option>

                                <option>America/New_York</option>
                            </select>
                        </div>

                        <div>
                            <label className="text-sm font-semibold">
                                Language
                            </label>

                            <select
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                                defaultValue={settings.locale ?? "en"}
                            >
                                <option value="en">English</option>

                                <option value="id">Bahasa Indonesia</option>
                            </select>
                        </div>

                        <div>
                            <label className="text-sm font-semibold">
                                Currency
                            </label>

                            <select
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                                defaultValue={settings.currency ?? "IDR"}
                            >
                                <option>IDR</option>
                                <option>USD</option>
                                <option>EUR</option>
                            </select>
                        </div>
                    </div>
                </div>

                {/* Branding */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Branding</h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <div>
                            <label className="text-sm font-semibold">
                                Logo URL
                            </label>

                            <input
                                type="text"
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                                placeholder="https://..."
                            />
                        </div>

                        <div>
                            <label className="text-sm font-semibold">
                                Favicon URL
                            </label>

                            <input
                                type="text"
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                                placeholder="https://..."
                            />
                        </div>
                    </div>
                </div>

                {/* Footer */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">
                        Footer Information
                    </h2>

                    <textarea
                        rows={4}
                        className="
                            w-full
                            rounded-2xl
                            border
                            p-4
                        "
                        defaultValue="
DIGESTEX
Global Textile Intelligence Ecosystem

Where Textile Meets Intelligence
                        "
                    />

                    <button
                        className="
                            mt-6
                            rounded-2xl
                            bg-emerald-600
                            px-6
                            py-3
                            font-semibold
                            text-white
                        "
                    >
                        Save Settings
                    </button>
                </div>
            </div>
        </AdminLayout>
    );
}
