import { Briefcase } from "lucide-react";

export default function BusinessPassport({ passport }) {
    const business = passport?.summary?.business ?? {};

    const Item = ({ label, value }) => (
        <div className="flex items-center justify-between border-b py-3 last:border-b-0">
            <span className="text-sm text-slate-500">{label}</span>

            <span className="font-medium">{value || "-"}</span>
        </div>
    );

    return (
        <div className="rounded-2xl border bg-white shadow-sm">
            <div className="flex items-center gap-3 border-b px-6 py-4">
                <Briefcase className="h-6 w-6 text-indigo-600" />

                <div>
                    <h2 className="text-xl font-bold">Business Passport</h2>

                    <p className="text-sm text-slate-500">
                        Business profile and products.
                    </p>
                </div>
            </div>

            <div className="grid gap-10 p-6 lg:grid-cols-2">
                <div>
                    <Item label="Industry" value={business.sector} />

                    <Item label="Category" value={business.category} />

                    <Item label="Products" value={business.products} />
                </div>

                <div>
                    <Item
                        label="Export Market"
                        value={business.export_market}
                    />

                    <Item label="Employees" value={business.employees} />

                    <Item label="Managing Director" value={business.director} />
                </div>
            </div>
        </div>
    );
}
