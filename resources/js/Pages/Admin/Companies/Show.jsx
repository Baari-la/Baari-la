import AdminLayout from "@/Layouts/AdminLayout";

import {
    Building2,
    Globe,
    ShieldCheck,
    Factory,
    Package,
    Network,
    Clock3,
    User,
    CheckCircle2,
    Eye,
} from "lucide-react";

import AdminStatusBadge from "@/Components/Admin/AdminStatusBadge";

export default function Show({ company, updates = [], claims = [] }) {
    return (
        <AdminLayout>
            <div className="space-y-8">
                {/* Header */}

                <div className="rounded-3xl bg-slate-900 p-10 text-white">
                    <p className="text-sm uppercase tracking-[0.3em] text-emerald-400">
                        COMPANY INTELLIGENCE
                    </p>

                    <h1 className="mt-3 text-5xl font-black">
                        {company.nama_perusahaan}
                    </h1>

                    <div className="mt-4 flex flex-wrap gap-4">
                        <AdminStatusBadge status={company.status_verifikasi} />

                        <div>{company.membership_type}</div>

                        <div>{company.country}</div>
                    </div>
                </div>

                {/* Overview */}

                <div className="grid gap-6 lg:grid-cols-4">
                    <Card
                        title="Products"
                        value={company.products?.length ?? 0}
                        icon={Package}
                    />

                    <Card
                        title="Markets"
                        value={company.markets?.length ?? 0}
                        icon={Globe}
                    />

                    <Card
                        title="Machines"
                        value={company.machines?.length ?? 0}
                        icon={Factory}
                    />

                    <Card
                        title="Certificates"
                        value={company.certifications?.length ?? 0}
                        icon={ShieldCheck}
                    />
                </div>

                {/* Company Passport */}

                <Section title="Company Passport™">
                    <Row label="Company" value={company.nama_perusahaan} />

                    <Row label="PIC" value={company.pimpinan} />

                    <Row label="Email" value={company.email_web} />

                    <Row label="Phone" value={company.telepon} />

                    <Row label="Employees" value={company.tenaga_kerja} />

                    <Row label="City" value={company.city} />
                </Section>

                {/* Products */}

                <Section title="Products">
                    {company.products?.map((item) => (
                        <div
                            key={item.id}
                            className="rounded-2xl bg-slate-50 p-4"
                        >
                            {item.product_name}
                        </div>
                    ))}
                </Section>

                {/* Markets */}

                <Section title="Export Markets">
                    {company.markets?.map((item) => (
                        <div
                            key={item.id}
                            className="rounded-2xl bg-slate-50 p-4"
                        >
                            {item.country}
                        </div>
                    ))}
                </Section>

                {/* Certifications */}

                <Section title="Certifications">
                    {company.certifications?.map((item) => (
                        <div
                            key={item.id}
                            className="rounded-2xl bg-slate-50 p-4"
                        >
                            {item.certification_name}
                        </div>
                    ))}
                </Section>

                {/* Smart Features */}

                <div className="grid gap-6 lg:grid-cols-3">
                    <Card title="Visibility Score™" value="84" icon={Eye} />

                    <Card title="Smart Matching™" value="12" icon={Network} />

                    <Card
                        title="Supply Chain™"
                        value="Ready"
                        icon={CheckCircle2}
                    />
                </div>

                {/* Update History */}

                <Section title="Update History">
                    {updates.map((item) => (
                        <div key={item.id} className="rounded-2xl border p-4">
                            <div className="font-bold">{item.status}</div>

                            <div className="text-sm text-slate-500">
                                {new Date(item.created_at).toLocaleString()}
                            </div>
                        </div>
                    ))}
                </Section>

                {/* Claim History */}

                <Section title="Claim History">
                    {claims.map((item) => (
                        <div key={item.id} className="rounded-2xl border p-4">
                            <div className="font-bold">{item.user?.name}</div>

                            <div className="text-sm text-slate-500">
                                {item.status}
                            </div>
                        </div>
                    ))}
                </Section>

                {/* Actions */}

                <div className="flex flex-wrap gap-4">
                    <button className="rounded-2xl bg-emerald-500 px-6 py-3 font-bold text-white">
                        Verify Company
                    </button>

                    <button className="rounded-2xl bg-slate-900 px-6 py-3 font-bold text-white">
                        Generate Passport™
                    </button>

                    <button className="rounded-2xl border px-6 py-3 font-bold">
                        Export PDF
                    </button>
                </div>
            </div>
        </AdminLayout>
    );
}

function Card({ title, value, icon: Icon }) {
    return (
        <div className="rounded-3xl border bg-white p-6">
            <div className="flex items-center justify-between">
                <div>
                    <div className="text-sm text-slate-500">{title}</div>

                    <div className="mt-2 text-3xl font-black">{value}</div>
                </div>

                <Icon className="h-7 w-7 text-slate-700" />
            </div>
        </div>
    );
}

function Section({ title, children }) {
    return (
        <div className="rounded-3xl border bg-white p-8">
            <h2 className="text-2xl font-black">{title}</h2>

            <div className="mt-6 space-y-4">{children}</div>
        </div>
    );
}

function Row({ label, value }) {
    return (
        <div className="flex items-center justify-between rounded-2xl bg-slate-50 p-4">
            <div className="font-semibold">{label}</div>

            <div className="font-black">{value || "-"}</div>
        </div>
    );
}
