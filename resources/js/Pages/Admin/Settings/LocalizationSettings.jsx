import AdminLayout from "@/Layouts/AdminLayout";
import { Head } from "@inertiajs/react";

export default function LocalizationSettings({ settings = {} }) {
    return (
        <AdminLayout>
            <Head title="Localization Settings" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        Localization Settings
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Configure language, timezone, currency, and regional
                        preferences across DIGESTEX.
                    </p>
                </div>

                {/* Language */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">
                        Language Settings
                    </h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <div>
                            <label className="font-semibold">
                                Default Language
                            </label>

                            <select
                                defaultValue={settings.locale ?? "en"}
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            >
                                <option value="en">English</option>

                                <option value="id">Bahasa Indonesia</option>

                                <option value="zh">Chinese</option>

                                <option value="ja">Japanese</option>
                            </select>
                        </div>

                        <div>
                            <label className="font-semibold">
                                Fallback Language
                            </label>

                            <select
                                defaultValue="en"
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            >
                                <option>English</option>
                                <option>Bahasa Indonesia</option>
                            </select>
                        </div>
                    </div>
                </div>

                {/* Regional */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">
                        Regional Settings
                    </h2>

                    <div className="grid gap-6 md:grid-cols-3">
                        <div>
                            <label className="font-semibold">Timezone</label>

                            <select
                                defaultValue={
                                    settings.timezone ?? "Asia/Jakarta"
                                }
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            >
                                <option>Asia/Jakarta</option>

                                <option>Asia/Singapore</option>

                                <option>UTC</option>

                                <option>America/New_York</option>
                            </select>
                        </div>

                        <div>
                            <label className="font-semibold">Currency</label>

                            <select
                                defaultValue={settings.currency ?? "IDR"}
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            >
                                <option>IDR</option>
                                <option>USD</option>
                                <option>EUR</option>
                                <option>JPY</option>
                                <option>CNY</option>
                            </select>
                        </div>

                        <div>
                            <label className="font-semibold">Country</label>

                            <select
                                defaultValue="Indonesia"
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            >
                                <option>Indonesia</option>
                                <option>Singapore</option>
                                <option>Vietnam</option>
                                <option>India</option>
                                <option>China</option>
                            </select>
                        </div>
                    </div>
                </div>

                {/* Date & Time */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">
                        Date & Time Format
                    </h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <div>
                            <label className="font-semibold">Date Format</label>

                            <select
                                defaultValue="d M Y"
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            >
                                <option>d M Y</option>

                                <option>Y-m-d</option>

                                <option>m/d/Y</option>

                                <option>d/m/Y</option>
                            </select>
                        </div>

                        <div>
                            <label className="font-semibold">Time Format</label>

                            <select
                                defaultValue="24"
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            >
                                <option value="24">24 Hours</option>

                                <option value="12">12 Hours</option>
                            </select>
                        </div>
                    </div>
                </div>

                {/* Numbers */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">
                        Number Formatting
                    </h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <div>
                            <label className="font-semibold">
                                Decimal Separator
                            </label>

                            <select
                                defaultValue="."
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            >
                                <option>.</option>
                                <option>,</option>
                            </select>
                        </div>

                        <div>
                            <label className="font-semibold">
                                Thousands Separator
                            </label>

                            <select
                                defaultValue=","
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            >
                                <option>,</option>
                                <option>.</option>
                                <option>Space</option>
                            </select>
                        </div>
                    </div>
                </div>

                {/* Statistics */}

                <div className="rounded-3xl bg-slate-900 p-8 text-white">
                    <h2 className="text-2xl font-black">
                        DIGESTEX Localization
                    </h2>

                    <div className="mt-6 grid gap-6 md:grid-cols-4">
                        <div>
                            <div className="text-slate-400 text-sm">
                                Supported Languages
                            </div>

                            <div className="text-3xl font-black">4</div>
                        </div>

                        <div>
                            <div className="text-slate-400 text-sm">
                                Supported Currencies
                            </div>

                            <div className="text-3xl font-black">5</div>
                        </div>

                        <div>
                            <div className="text-slate-400 text-sm">
                                Timezones
                            </div>

                            <div className="text-3xl font-black">4</div>
                        </div>

                        <div>
                            <div className="text-slate-400 text-sm">
                                Default Locale
                            </div>

                            <div className="text-xl font-black">EN</div>
                        </div>
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
                        Save Localization Settings
                    </button>
                </div>
            </div>
        </AdminLayout>
    );
}
