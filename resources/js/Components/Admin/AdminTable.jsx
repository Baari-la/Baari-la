import { Link } from "@inertiajs/react";
import { Eye } from "lucide-react";

export default function AdminTable({
    columns = [],
    data = [],
    actions = true,
    routeName = null,
    emptyMessage = "No records found.",
}) {
    return (
        <div
            className="
                overflow-hidden
                rounded-3xl
                border
                bg-white
                shadow-sm
            "
        >
            <table className="min-w-full">
                {/* Header */}

                <thead
                    className="
                        bg-slate-50
                        text-left
                    "
                >
                    <tr>
                        {columns.map((column) => (
                            <th
                                key={column.key}
                                className="
                                    px-6
                                    py-5
                                    text-sm
                                    font-bold
                                    text-slate-700
                                "
                            >
                                {column.label}
                            </th>
                        ))}

                        {actions && <th className="px-6 py-5">Actions</th>}
                    </tr>
                </thead>

                {/* Body */}

                <tbody>
                    {data.length === 0 && (
                        <tr>
                            <td
                                colSpan={columns.length + (actions ? 1 : 0)}
                                className="
                                    px-6
                                    py-16
                                    text-center
                                    text-slate-500
                                "
                            >
                                {emptyMessage}
                            </td>
                        </tr>
                    )}

                    {data.map((row) => (
                        <tr
                            key={row.id}
                            className="
                                border-t
                                transition
                                hover:bg-slate-50
                            "
                        >
                            {columns.map((column) => (
                                <td
                                    key={column.key}
                                    className="
                                            px-6
                                            py-5
                                        "
                                >
                                    {row[column.key] ?? "-"}
                                </td>
                            ))}

                            {actions && (
                                <td className="px-6 py-5">
                                    {routeName && (
                                        <Link
                                            href={route(routeName, row.id)}
                                            className="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-xl
                                                bg-slate-900
                                                px-4
                                                py-2
                                                text-sm
                                                font-semibold
                                                text-white
                                            "
                                        >
                                            <Eye className="h-4 w-4" />
                                            View
                                        </Link>
                                    )}
                                </td>
                            )}
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}
