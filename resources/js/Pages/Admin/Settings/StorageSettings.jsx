import AdminLayout from "@/Layouts/AdminLayout";
import { Head } from "@inertiajs/react";

export default function StorageSettings({ settings = {} }) {
    return (
        <AdminLayout>
            <Head title="Storage Settings" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        Storage Settings
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Configure file storage, backups, and cloud integrations
                        for the DIGESTEX ecosystem.
                    </p>
                </div>

                {/* Default Storage */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Default Storage</h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <div>
                            <label className="font-semibold">
                                Storage Driver
                            </label>

                            <select
                                defaultValue={
                                    settings.storage_driver ?? "local"
                                }
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            >
                                <option>local</option>
                                <option>public</option>
                                <option>s3</option>
                                <option>cloudflare-r2</option>
                                <option>google-cloud</option>
                                <option>azure</option>
                            </select>
                        </div>

                        <div>
                            <label className="font-semibold">
                                File Visibility
                            </label>

                            <select
                                defaultValue="private"
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            >
                                <option>private</option>
                                <option>public</option>
                            </select>
                        </div>
                    </div>
                </div>

                {/* Cloud Storage */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Amazon S3</h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <input
                            type="text"
                            placeholder="AWS Access Key"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />

                        <input
                            type="password"
                            placeholder="AWS Secret Key"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />

                        <input
                            type="text"
                            placeholder="Bucket Name"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />

                        <input
                            type="text"
                            placeholder="Region"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />
                    </div>
                </div>

                {/* Cloudflare R2 */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Cloudflare R2</h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <input
                            type="text"
                            placeholder="Account ID"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />

                        <input
                            type="password"
                            placeholder="API Token"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />

                        <input
                            type="text"
                            placeholder="Bucket"
                            className="
                                rounded-2xl
                                border
                                p-3
                            "
                        />
                    </div>
                </div>

                {/* Backup */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">Backup Settings</h2>

                    <div className="space-y-4">
                        <label className="flex items-center gap-3">
                            <input type="checkbox" defaultChecked />
                            Enable Automatic Backups
                        </label>

                        <label className="flex items-center gap-3">
                            <input type="checkbox" defaultChecked />
                            Backup Database
                        </label>

                        <label className="flex items-center gap-3">
                            <input type="checkbox" defaultChecked />
                            Backup Uploaded Files
                        </label>
                    </div>

                    <div className="mt-6 grid gap-6 md:grid-cols-2">
                        <div>
                            <label className="font-semibold">
                                Backup Frequency
                            </label>

                            <select
                                defaultValue="daily"
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    p-3
                                "
                            >
                                <option>hourly</option>
                                <option>daily</option>
                                <option>weekly</option>
                                <option>monthly</option>
                            </select>
                        </div>

                        <div>
                            <label className="font-semibold">
                                Retention (days)
                            </label>

                            <input
                                type="number"
                                defaultValue={30}
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

                {/* Statistics */}

                <div className="rounded-3xl bg-slate-900 p-8 text-white">
                    <h2 className="text-2xl font-black">Storage Overview</h2>

                    <div className="mt-6 grid gap-6 md:grid-cols-4">
                        <div>
                            <div className="text-sm text-slate-400">
                                Total Files
                            </div>

                            <div className="text-3xl font-black">12,845</div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-400">
                                Storage Used
                            </div>

                            <div className="text-3xl font-black">18 GB</div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-400">
                                Last Backup
                            </div>

                            <div className="text-xl font-black">Today</div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-400">
                                Backup Status
                            </div>

                            <div className="text-xl font-black text-emerald-400">
                                Healthy
                            </div>
                        </div>
                    </div>
                </div>

                {/* Actions */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <div className="flex gap-4">
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
                            Run Backup Now
                        </button>

                        <button
                            className="
                                rounded-2xl
                                bg-emerald-600
                                px-6
                                py-3
                                font-semibold
                                text-white
                            "
                        >
                            Save Storage Settings
                        </button>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
