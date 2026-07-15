import WebsiteLayout from "@/Layouts/WebsiteLayout";

export default function Index({ summary = {}, groups = {} }) {
    return (
        <WebsiteLayout title="Master Data Explorer">
            <div className="space-y-6">
                {/* ===================================================
                    HERO
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-8">
                    <h1 className="text-3xl font-bold text-slate-900">
                        Master Data Explorer
                    </h1>

                    <p className="mt-2 text-slate-600">
                        Browse all Master Data schemas, namespaces,
                        relationships, and metadata powering the DIGESTEX
                        Intelligence Platform.
                    </p>
                </div>

                {/* ===================================================
                    SUMMARY
                =================================================== */}

                <div
                    className="
                    grid
                    grid-cols-2
                    md:grid-cols-4
                    gap-6
                "
                >
                    <MetricCard title="Schemas" value={summary.schemas ?? 16} />

                    <MetricCard
                        title="Namespaces"
                        value={summary.namespaces ?? 4}
                    />

                    <MetricCard
                        title="Relationships"
                        value={summary.relationships ?? 18}
                    />

                    <MetricCard title="Registry" value="ACTIVE" />
                </div>

                {/* ===================================================
                    BUSINESS
                =================================================== */}

                <NamespaceCard
                    title="Business"
                    items={
                        groups.business ?? [
                            "business_ecosystems",
                            "business_roles",
                            "buyer_segments",
                            "supplier_segments",
                        ]
                    }
                />

                {/* ===================================================
                    PRODUCTS
                =================================================== */}

                <NamespaceCard
                    title="Products"
                    items={
                        groups.products ?? [
                            "product_categories",
                            "product_applications",
                            "product_statuses",
                        ]
                    }
                />

                {/* ===================================================
                    CERTIFICATION
                =================================================== */}

                <NamespaceCard
                    title="Certification"
                    items={
                        groups.certification ?? [
                            "certifications",
                            "certification_markets",
                            "certification_scopes",
                            "certification_categories",
                        ]
                    }
                />

                {/* ===================================================
                    MACHINERY
                =================================================== */}

                <NamespaceCard
                    title="Machinery"
                    items={groups.machinery ?? ["machinery_categories"]}
                />

                {/* ===================================================
                    SYSTEM
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-6">
                    <h2 className="text-lg font-semibold mb-6">
                        System Information
                    </h2>

                    <div className="grid grid-cols-2 gap-6">
                        <InfoItem label="Schema Registry" value="READY" />

                        <InfoItem label="Schema Identity" value="ACTIVE" />

                        <InfoItem label="Knowledge Graph" value="CONNECTED" />

                        <InfoItem label="Validation" value="PASSED" />

                        <InfoItem label="Schema Loader" value="ACTIVE" />

                        <InfoItem label="Repository" value="READY" />
                    </div>
                </div>

                {/* ===================================================
                    MASTER DATA HEALTH
                =================================================== */}

                <div className="bg-white rounded-2xl shadow-sm p-6">
                    <h2 className="text-lg font-semibold mb-4">
                        Master Data Health
                    </h2>

                    <div className="space-y-3">
                        <StatusItem
                            status="SUCCESS"
                            message="16 schemas successfully registered."
                        />

                        <StatusItem
                            status="SUCCESS"
                            message="Schema Registry initialized."
                        />

                        <StatusItem
                            status="SUCCESS"
                            message="Knowledge Graph connected."
                        />

                        <StatusItem
                            status="INFO"
                            message="18 relationships detected."
                        />

                        <StatusItem
                            status="WARNING"
                            message="4 orphan taxonomy nodes detected."
                        />
                    </div>
                </div>
            </div>
        </WebsiteLayout>
    );
}

/* ==========================================================
   COMPONENTS
========================================================== */

function MetricCard({ title, value }) {
    return (
        <div className="bg-white rounded-2xl shadow-sm p-6">
            <p className="text-sm text-slate-500">{title}</p>

            <p className="text-3xl font-bold mt-2">{value}</p>
        </div>
    );
}

function NamespaceCard({ title, items }) {
    return (
        <div className="bg-white rounded-2xl shadow-sm p-6">
            <h2 className="text-lg font-semibold mb-4">{title}</h2>

            <div className="grid md:grid-cols-2 gap-3">
                {items.map((item) => (
                    <div
                        key={item}
                        className="
                            border
                            rounded-lg
                            px-4
                            py-3
                        "
                    >
                        {item}
                    </div>
                ))}
            </div>
        </div>
    );
}

function InfoItem({ label, value }) {
    return (
        <div>
            <p className="text-sm text-slate-500">{label}</p>

            <p className="font-semibold">{value}</p>
        </div>
    );
}

function StatusItem({ status, message }) {
    return (
        <div className="flex gap-4">
            <div className="font-semibold min-w-[90px]">{status}</div>

            <div>{message}</div>
        </div>
    );
}
