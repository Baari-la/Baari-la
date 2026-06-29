import Card from "../Layout/Card";

export default function DataTable({
    columns = [],

    data = [],

    children,
}) {
    return (
        <Card className="overflow-hidden p-0">
            <div className="overflow-x-auto">
                <table className="min-w-full divide-y divide-slate-200">
                    <thead className="bg-slate-50">
                        <tr>
                            {columns.map((column) => (
                                <th
                                    key={column.key}
                                    className="px-6 py-4 text-left text-xs font-bold uppercase tracking-widest text-slate-500"
                                >
                                    {column.label}
                                </th>
                            ))}
                        </tr>
                    </thead>

                    <tbody className="divide-y divide-slate-100 bg-white">
                        {children}
                    </tbody>
                </table>
            </div>
        </Card>
    );
}
