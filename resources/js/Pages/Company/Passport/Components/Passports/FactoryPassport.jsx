import { Factory, Cog, Boxes, Truck } from "lucide-react";

export default function FactoryPassport({ passport }) {
    const factory = passport?.passport?.factory ?? {};

    const capacity = passport?.passport?.capacity ?? {};

    const machinery = passport?.passport?.machinery ?? {};

    const logistics = passport?.passport?.logistics ?? {};

    const Card = ({ icon: Icon, title, value }) => (
        <div className="rounded-xl border bg-slate-50 p-5">
            <div className="flex items-center justify-between">
                <div>
                    <p className="text-sm text-slate-500">{title}</p>

                    <h3 className="mt-2 text-3xl font-bold">{value}</h3>
                </div>

                <Icon className="h-8 w-8 text-slate-500" />
            </div>
        </div>
    );

    return (
        <div className="rounded-2xl border bg-white shadow-sm">
            <div className="flex items-center gap-3 border-b px-6 py-4">
                <Factory className="h-6 w-6 text-emerald-600" />

                <div>
                    <h2 className="text-xl font-bold">Factory Passport</h2>

                    <p className="text-sm text-slate-500">
                        Manufacturing facilities and production capability.
                    </p>
                </div>
            </div>

            <div className="grid gap-6 p-6 md:grid-cols-2 xl:grid-cols-4">
                <Card
                    icon={Factory}
                    title="Factory"
                    value={factory.is_complete ? "Available" : "-"}
                />

                <Card
                    icon={Cog}
                    title="Machines"
                    value={machinery.count ?? 0}
                />

                <Card
                    icon={Boxes}
                    title="Capacities"
                    value={capacity.count ?? 0}
                />

                <Card
                    icon={Truck}
                    title="Logistics"
                    value={logistics.is_complete ? "Ready" : "-"}
                />
            </div>
        </div>
    );
}
