import AdminLayout from "@/Layouts/AdminLayout";
import { Head } from "@inertiajs/react";

export default function MembershipSettings({ settings = {} }) {
    const memberships = [
        {
            name: "Free",
            description:
                "Basic access to public information and directory browsing.",
            access: "free",
        },

        {
            name: "API Member",
            description:
                "Access to API Jakarta member benefits and privileged content.",
            access: "api_member",
        },

        {
            name: "Premium",
            description:
                "Access to premium company profiles and business intelligence.",
            access: "premium",
        },

        {
            name: "Executive",
            description:
                "Executive dashboards and advanced intelligence features.",
            access: "executive",
        },

        {
            name: "Founding Partner",
            description:
                "Strategic partner access across the DIGESTEX ecosystem.",
            access: "founding_partner",
        },
    ];

    return (
        <AdminLayout>
            <Head title="Membership Settings" />

            <div className="space-y-8">
                {/* Header */}

                <div>
                    <h1 className="text-3xl font-black text-slate-900">
                        Membership Settings
                    </h1>

                    <p className="mt-2 text-slate-500">
                        Configure membership plans, pricing, access levels, and
                        platform privileges.
                    </p>
                </div>

                {/* Membership Overview */}

                <div className="grid gap-6 md:grid-cols-5">
                    {memberships.map((membership) => (
                        <div
                            key={membership.name}
                            className="
                                rounded-3xl
                                bg-white
                                p-6
                                shadow-sm
                            "
                        >
                            <div className="text-lg font-black">
                                {membership.name}
                            </div>

                            <p className="mt-3 text-sm text-slate-500">
                                {membership.description}
                            </p>

                            <div className="mt-4 text-sm font-semibold text-emerald-600">
                                {membership.access}
                            </div>
                        </div>
                    ))}
                </div>

                {/* Pricing */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">
                        Membership Pricing
                    </h2>

                    <div className="grid gap-6 md:grid-cols-2">
                        <div>
                            <label className="font-semibold">API Member</label>

                            <input
                                type="number"
                                defaultValue={settings.api_price ?? 0}
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
                            <label className="font-semibold">Premium</label>

                            <input
                                type="number"
                                defaultValue={settings.premium_price ?? 2500000}
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
                            <label className="font-semibold">Executive</label>

                            <input
                                type="number"
                                defaultValue={
                                    settings.executive_price ?? 10000000
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
                            <label className="font-semibold">
                                Founding Partner
                            </label>

                            <input
                                type="number"
                                defaultValue={
                                    settings.founding_partner_price ?? 50000000
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

                {/* Feature Access */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="mb-6 text-xl font-bold">
                        Feature Access Matrix
                    </h2>

                    <table className="min-w-full">
                        <thead>
                            <tr className="border-b">
                                <th className="py-3 text-left">Feature</th>

                                <th>Free</th>
                                <th>API</th>
                                <th>Premium</th>
                                <th>Executive</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr className="border-b">
                                <td className="py-4">Company Directory</td>

                                <td>✓</td>
                                <td>✓</td>
                                <td>✓</td>
                                <td>✓</td>
                            </tr>

                            <tr className="border-b">
                                <td className="py-4">Trade Intelligence</td>

                                <td>-</td>
                                <td>✓</td>
                                <td>✓</td>
                                <td>✓</td>
                            </tr>

                            <tr className="border-b">
                                <td className="py-4">Premium Profiles</td>

                                <td>-</td>
                                <td>-</td>
                                <td>✓</td>
                                <td>✓</td>
                            </tr>

                            <tr className="border-b">
                                <td className="py-4">Executive Dashboard</td>

                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>✓</td>
                            </tr>

                            <tr>
                                <td className="py-4">Build My Supply Chain</td>

                                <td>-</td>
                                <td>✓</td>
                                <td>✓</td>
                                <td>✓</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {/* Footer */}

                <div className="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 className="text-xl font-bold">Membership Status</h2>

                    <div className="mt-6 grid gap-4 md:grid-cols-4">
                        <div>
                            <div className="text-sm text-slate-500">
                                Total Members
                            </div>

                            <div className="text-2xl font-black">0</div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-500">
                                Premium
                            </div>

                            <div className="text-2xl font-black">0</div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-500">
                                Executive
                            </div>

                            <div className="text-2xl font-black">0</div>
                        </div>

                        <div>
                            <div className="text-sm text-slate-500">
                                Founding Partners
                            </div>

                            <div className="text-2xl font-black">0</div>
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
                        Save Membership Settings
                    </button>
                </div>
            </div>
        </AdminLayout>
    );
}
