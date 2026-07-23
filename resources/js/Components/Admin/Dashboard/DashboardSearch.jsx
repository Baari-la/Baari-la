import { useState } from "react";

import AdminSearchBar from "@/Components/Admin/AdminSearchBar";

export default function DashboardSearch() {
    const [search, setSearch] = useState("");

    const suggestions = [
        "PT Apparel One",
        "Executive Partner",
        "Pending Payments",
        "Build My Supply Chain",
        "Uniqlo",
        "Digital Company Passport",
    ];

    return (
        <div className="space-y-6">
            {/* Header */}

            <div>
                <h2 className="text-2xl font-black">Global Search</h2>

                <p className="mt-2 text-slate-500">
                    Search companies, participants, products, buyers, suppliers,
                    and DIGESTEX services.
                </p>
            </div>

            {/* Search */}

            <AdminSearchBar
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                onClear={() => setSearch("")}
                placeholder="
                    Search companies, products, participants...
                "
            />

            {/* Suggestions */}

            <div className="flex flex-wrap gap-3">
                {suggestions.map((item) => (
                    <button
                        key={item}
                        type="button"
                        onClick={() => setSearch(item)}
                        className="
                            rounded-full
                            bg-slate-100
                            px-4
                            py-2
                            text-sm
                            font-medium
                            transition
                            hover:bg-emerald-100
                            hover:text-emerald-700
                        "
                    >
                        {item}
                    </button>
                ))}
            </div>

            {/* Search Hint */}

            {search && (
                <div
                    className="
                        rounded-2xl
                        border
                        bg-white
                        p-5
                        text-sm
                        text-slate-500
                        shadow-sm
                    "
                >
                    Searching for:
                    <span className="ml-2 font-bold text-slate-900">
                        {search}
                    </span>
                    <p className="mt-2">Future versions will search across:</p>
                    <ul className="mt-2 list-disc space-y-1 pl-5">
                        <li>Companies</li>
                        <li>Digital Directory Participants</li>
                        <li>Products</li>
                        <li>Executive Intelligence</li>
                        <li>Build My Supply Chain™</li>
                        <li>Trade Data & HS Codes</li>
                    </ul>
                </div>
            )}
        </div>
    );
}
