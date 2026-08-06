/*
|--------------------------------------------------------------------------
| DIGESTEX Industry Header™
|--------------------------------------------------------------------------
|
| Universal header driven by Industry Blueprint™.
|
| Every Industry Blueprint provides:
|
| • Title
| • Description
| • Icon (optional)
| • Theme Color (optional)
|
|--------------------------------------------------------------------------
*/

import { Building2 } from "lucide-react";

export default function IndustryHeader({ blueprint }) {
    /*
    |--------------------------------------------------------------------------
    | Blueprint
    |--------------------------------------------------------------------------
    */

    const {
        title = "Industry Blueprint™",
        description = "",
        icon: Icon = Building2,
        color = "indigo",
    } = blueprint ?? {};

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    */

    const themes = {
        indigo: {
            background: "bg-indigo-100",
            text: "text-indigo-600",
        },

        emerald: {
            background: "bg-emerald-100",
            text: "text-emerald-600",
        },

        blue: {
            background: "bg-blue-100",
            text: "text-blue-600",
        },

        amber: {
            background: "bg-amber-100",
            text: "text-amber-600",
        },

        rose: {
            background: "bg-rose-100",
            text: "text-rose-600",
        },
    };

    const theme = themes[color] ?? themes.indigo;

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div className="flex items-start gap-5">
                <div className={`rounded-2xl p-4 ${theme.background}`}>
                    <Icon className={`h-7 w-7 ${theme.text}`} />
                </div>

                <div className="flex-1">
                    <h1 className="text-3xl font-black tracking-tight text-slate-900">
                        {title}
                    </h1>

                    <p className="mt-3 max-w-3xl text-sm leading-7 text-slate-600">
                        {description}
                    </p>
                </div>
            </div>
        </div>
    );
}
