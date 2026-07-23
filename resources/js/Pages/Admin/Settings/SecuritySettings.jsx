import AdminLayout from "@/Layouts/AdminLayout";
import { Head } from "@inertiajs/react";

export default function SecuritySettings({ settings = {} }) {
    return (
        <AdminLayout>
            <Head title="Security Settings" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        Security Settings
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Configure authentication, session policies, access
                        control, and platform security.
                    </p>
                </div>

                {/* Authentication */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Authentication</h2>

                    <div className="space-y-4">
                        <label className="flex items-center gap-3">
                            <input
                                type="checkbox"
                                defaultChecked={
                                    settings.email_verification ?? true
                                }
                            />
                            Require Email Verification
                        </label>

                        <label className="flex items-center gap-3">
                            <input
                                type="checkbox"
                                defaultChecked={settings.google_login ?? true}
                            />
                            Enable Google Login
                        </label>

                        <label className="flex items-center gap-3">
                            <input
                                type="checkbox"
                                defaultChecked={settings.two_factor ?? false}
                            />
                            Enable Two-Factor Authentication
                        </label>
                    </div>
                </div>

                {/* Session Management */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">
                        Session Management
                    </h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <div>
                            <label className="font-semibold">
                                Session Timeout (minutes)
                            </label>

                            <input
                                type="number"
                                defaultValue={settings.session_timeout ?? 120}
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
                            <label className="font-semibold">
                                Max Concurrent Sessions
                            </label>

                            <input
                                type="number"
                                defaultValue={settings.max_sessions ?? 3}
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

                {/* Login Protection */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Login Protection</h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <div>
                            <label className="font-semibold">
                                Maximum Login Attempts
                            </label>

                            <input
                                type="number"
                                defaultValue={settings.max_login_attempts ?? 5}
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
                            <label className="font-semibold">
                                Lockout Duration (minutes)
                            </label>

                            <input
                                type="number"
                                defaultValue={settings.lockout_duration ?? 15}
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

                {/* Password Policy */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Password Policy</h2>

                    <div className="space-y-4">
                        <label className="flex items-center gap-3">
                            <input type="checkbox" defaultChecked />
                            Minimum 8 Characters
                        </label>

                        <label className="flex items-center gap-3">
                            <input type="checkbox" defaultChecked />
                            Require Uppercase Letter
                        </label>

                        <label className="flex items-center gap-3">
                            <input type="checkbox" defaultChecked />
                            Require Number
                        </label>

                        <label className="flex items-center gap-3">
                            <input type="checkbox" defaultChecked />
                            Require Special Character
                        </label>
                    </div>
                </div>

                {/* Access Control */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Access Control</h2>

                    <div className="grid gap-6 md:grid-cols-3">
                        <div className="rounded-2xl border p-4">
                            <div className="font-bold">Free Users</div>

                            <div className="mt-2 text-sm text-slate-500">
                                Public access only.
                            </div>
                        </div>

                        <div className="rounded-2xl border p-4">
                            <div className="font-bold">Premium Users</div>

                            <div className="mt-2 text-sm text-slate-500">
                                Premium features enabled.
                            </div>
                        </div>

                        <div className="rounded-2xl border p-4">
                            <div className="font-bold">Administrators</div>

                            <div className="mt-2 text-sm text-slate-500">
                                Full platform management.
                            </div>
                        </div>
                    </div>
                </div>

                {/* Security Statistics */}

                <div className="rounded-3xl bg-slate-900 p-8 text-white">
                    <h2 className="text-2xl font-black">Security Overview</h2>

                    <div className="mt-6 grid gap-6 md:grid-cols-4">
                        <div>
                            <div className="text-sm text-slate-400">
                                Active Sessions
                            </div>

                            <div className="text-3xl font-black">24</div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-400">
                                Failed Logins
                            </div>

                            <div className="text-3xl font-black">3</div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-400">
                                2FA Enabled
                            </div>

                            <div className="text-3xl font-black">12</div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-400">
                                Security Score
                            </div>

                            <div className="text-3xl font-black text-emerald-400">
                                A+
                            </div>
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
                        Save Security Settings
                    </button>
                </div>
            </div>
        </AdminLayout>
    );
}
