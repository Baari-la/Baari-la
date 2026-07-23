import AdminLayout from "@/Layouts/AdminLayout";
import { Head } from "@inertiajs/react";

export default function QueueManagement({ stats = {}, failedJobs = [] }) {
    return (
        <AdminLayout>
            <Head title="Queue Management" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        Queue Management
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Monitor background jobs, queue workers, failed jobs, and
                        processing performance.
                    </p>
                </div>

                {/* Statistics */}

                <div className="grid gap-6 md:grid-cols-4">
                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Pending Jobs
                        </div>

                        <div className="mt-2 text-3xl font-black">
                            {stats.pending ?? 0}
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">Processing</div>

                        <div className="mt-2 text-3xl font-black">
                            {stats.processing ?? 0}
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">Completed</div>

                        <div className="mt-2 text-3xl font-black">
                            {stats.completed ?? 0}
                        </div>
                    </div>

                    <div className="rounded-3xl bg-white p-6 shadow-sm">
                        <div className="text-sm text-slate-500">
                            Failed Jobs
                        </div>

                        <div className="mt-2 text-3xl font-black text-red-600">
                            {stats.failed ?? 0}
                        </div>
                    </div>
                </div>

                {/* Queue Configuration */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">
                        Queue Configuration
                    </h2>

                    <div className="grid gap-6 md:grid-cols-3">
                        <div>
                            <label className="font-semibold">
                                Queue Driver
                            </label>

                            <input
                                type="text"
                                readOnly
                                value={stats.driver ?? "database"}
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    bg-slate-50
                                    p-3
                                "
                            />
                        </div>

                        <div>
                            <label className="font-semibold">
                                Active Workers
                            </label>

                            <input
                                type="text"
                                readOnly
                                value={stats.workers ?? 1}
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    bg-slate-50
                                    p-3
                                "
                            />
                        </div>

                        <div>
                            <label className="font-semibold">
                                Retry Attempts
                            </label>

                            <input
                                type="text"
                                readOnly
                                value={stats.retry ?? 3}
                                className="
                                    mt-2
                                    w-full
                                    rounded-2xl
                                    border
                                    bg-slate-50
                                    p-3
                                "
                            />
                        </div>
                    </div>
                </div>

                {/* Failed Jobs */}

                <div className="overflow-hidden rounded-3xl bg-white shadow-sm">
                    <div className="border-b p-6">
                        <h2 className="text-xl font-bold">Failed Jobs</h2>
                    </div>

                    <table className="min-w-full">
                        <thead className="bg-slate-50">
                            <tr>
                                <th className="px-6 py-4 text-left">ID</th>

                                <th className="px-6 py-4 text-left">Queue</th>

                                <th className="px-6 py-4 text-left">
                                    Exception
                                </th>

                                <th className="px-6 py-4 text-left">
                                    Failed At
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            {failedJobs.map((job) => (
                                <tr key={job.id} className="border-t">
                                    <td className="px-6 py-4">{job.id}</td>

                                    <td className="px-6 py-4">{job.queue}</td>

                                    <td className="px-6 py-4">
                                        <div className="max-w-md truncate">
                                            {job.exception}
                                        </div>
                                    </td>

                                    <td className="px-6 py-4">
                                        {job.failed_at}
                                    </td>
                                </tr>
                            ))}

                            {failedJobs.length === 0 && (
                                <tr>
                                    <td
                                        colSpan="4"
                                        className="
                                            px-6
                                            py-12
                                            text-center
                                            text-slate-500
                                        "
                                    >
                                        No failed jobs found.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
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
                            Restart Workers
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
                            Retry Failed Jobs
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
                            Clear Failed Jobs
                        </button>
                    </div>
                </div>

                {/* Overview */}

                <div className="rounded-3xl bg-slate-900 p-8 text-white">
                    <h2 className="text-2xl font-black">Queue Health</h2>

                    <div className="mt-6 grid gap-6 md:grid-cols-4">
                        <div>
                            <div className="text-sm text-slate-400">Driver</div>

                            <div className="text-xl font-black">
                                {stats.driver ?? "database"}
                            </div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-400">
                                Workers
                            </div>

                            <div className="text-xl font-black">
                                {stats.workers ?? 1}
                            </div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-400">Uptime</div>

                            <div className="text-xl font-black">99.9%</div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-400">Status</div>

                            <div className="text-xl font-black text-emerald-400">
                                Healthy
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
