import AdminLayout from "@/Layouts/AdminLayout";
import { Head } from "@inertiajs/react";

export default function SystemHealth({ health = {} }) {
    const services = [
        {
            name: "Application",
            status: "Healthy",
        },
        {
            name: "Laravel",
            status: "Healthy",
        },
        {
            name: "MySQL",
            status: "Healthy",
        },
        {
            name: "Redis",
            status: "Healthy",
        },
        {
            name: "Queue",
            status: "Healthy",
        },
        {
            name: "Cache",
            status: "Healthy",
        },
    ];

    return (
        <AdminLayout>
            <Head title="System Health" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        System Health
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Monitor infrastructure, application performance, and
                        overall DIGESTEX platform health.
                    </p>
                </div>

                {/* Summary */}

                <div className="grid gap-6 md:grid-cols-4">
                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Overall Status
                        </div>

                        <div className="mt-2 text-2xl font-black text-emerald-600">
                            Healthy
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">Uptime</div>

                        <div className="mt-2 text-2xl font-black">99.9%</div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            PHP Version
                        </div>

                        <div className="mt-2 text-2xl font-black">8.2.12</div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">Laravel</div>

                        <div className="mt-2 text-2xl font-black">12.x</div>
                    </div>
                </div>

                {/* Services */}

                <div className="rounded-3xl bg-white shadow-sm overflow-hidden">
                    <div className="border-b p-6">
                        <h2 className="text-xl font-bold">Service Status</h2>
                    </div>

                    <table className="min-w-full">
                        <thead className="bg-slate-50">
                            <tr>
                                <th className="px-6 py-4 text-left">Service</th>

                                <th className="px-6 py-4 text-left">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            {services.map((service) => (
                                <tr key={service.name} className="border-t">
                                    <td className="px-6 py-4 font-semibold">
                                        {service.name}
                                    </td>

                                    <td className="px-6 py-4">
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
                                            {service.status}
                                        </span>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                {/* Resources */}

                <div className="grid gap-6 md:grid-cols-3">
                    <div className="rounded-3xl bg-white p-8 shadow-sm">
                        <h2 className="text-xl font-bold">Memory Usage</h2>

                        <div className="mt-4 text-4xl font-black">256 MB</div>

                        <div className="mt-2 text-slate-500">
                            Current application memory usage.
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-8 shadow-sm">
                        <h2 className="text-xl font-bold">Disk Usage</h2>

                        <div className="mt-4 text-4xl font-black">18 GB</div>

                        <div className="mt-2 text-slate-500">
                            Total storage consumed.
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-8 shadow-sm">
                        <h2 className="text-xl font-bold">Database Size</h2>

                        <div className="mt-4 text-4xl font-black">1.2 GB</div>

                        <div className="mt-2 text-slate-500">
                            Total MySQL database usage.
                        </div>
                    </div>
                </div>

                {/* Environment */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">
                        Environment Information
                    </h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <div>
                            <div className="text-sm text-slate-500">
                                APP_ENV
                            </div>

                            <div className="text-lg font-bold">Production</div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-500">
                                APP_DEBUG
                            </div>

                            <div className="text-lg font-bold">False</div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-500">
                                Queue Driver
                            </div>

                            <div className="text-lg font-bold">Database</div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-500">
                                Cache Driver
                            </div>

                            <div className="text-lg font-bold">File</div>
                        </div>
                    </div>
                </div>

                {/* Actions */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <div className="flex flex-wrap gap-4">
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
                            Refresh Status
                        </button>

                        <button
                            className="
                                rounded-2xl
                                bg-amber-600
                                px-6
                                py-3
                                font-semibold
                                text-white
                            "
                        >
                            Clear Cache
                        </button>

                        <button
                            className="
                                rounded-2xl
                                bg-red-600
                                px-6
                                py-3
                                font-semibold
                                text-white
                            "
                        >
                            Restart Queue
                        </button>
                    </div>
                </div>

                {/* Footer */}

                <div className="rounded-3xl bg-slate-900 p-8 text-white">
                    <h2 className="text-2xl font-black">
                        DIGESTEX Executive Console
                    </h2>

                    <p className="mt-3 text-slate-300">
                        Real-time monitoring for the Global Textile Intelligence
                        Ecosystem.
                    </p>

                    <div className="mt-6">
                        <span
                            className="
                                rounded-full
                                bg-emerald-500
                                px-4
                                py-2
                                text-sm
                                font-bold
                            "
                        >
                            ALL SYSTEMS OPERATIONAL
                        </span>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
