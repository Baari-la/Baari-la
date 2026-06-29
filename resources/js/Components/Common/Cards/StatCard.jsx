import Card from "../Layout/Card";

export default function StatCard({
    label,

    value,

    description,
}) {
    return (
        <Card hover={false}>
            <p className="text-sm font-semibold uppercase tracking-widest text-slate-500">
                {label}
            </p>

            <h2 className="mt-4 text-5xl font-black text-slate-900">{value}</h2>

            {description && (
                <p className="mt-4 text-sm leading-6 text-slate-500">
                    {description}
                </p>
            )}
        </Card>
    );
}
