import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, Link, useForm, usePage } from "@inertiajs/react";

export default function Register() {
    const { props } = usePage();

    const isEn = props.locale === "en";
    const { data, setData, post, processing, errors, reset } = useForm({
        name: "",
        email: "",
        phone: "",
        position: "",
        password: "",
        password_confirmation: "",
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("register"), {
            onFinish: () => reset("password", "password_confirmation"),
        });
    };

    return (
        <GuestLayout>
            <Head title="Register | DIGESTEX" />
            <div className="mb-8 text-center">
                <h2
                    className="
                    text-2xl
                    font-black
                    uppercase
                    italic
                    tracking-tighter
                    text-white
                "
                >
                    {isEn ? "Create Your " : "Buat "}

                    <span className="text-yellow-500">DIGESTEX Account</span>
                </h2>

                <p
                    className="
                    mt-2
                    text-[10px]
                    font-bold
                    uppercase
                    tracking-[0.3em]
                    text-gray-200
                "
                >
                    {isEn
                        ? "Join the Global Textile Intelligence Ecosystem"
                        : "Bergabung dengan Global Textile Intelligence Ecosystem"}
                </p>
            </div>

            <form onSubmit={submit} className="space-y-5">
                <div>
                    <InputLabel htmlFor="name" value="Name" />

                    <TextInput
                        id="name"
                        name="name"
                        value={data.name}
                        className="mt-1 block w-full"
                        autoComplete="name"
                        isFocused={true}
                        onChange={(e) => setData("name", e.target.value)}
                        required
                    />

                    <InputError message={errors.name} className="mt-2" />
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        onChange={(e) => setData("email", e.target.value)}
                        required
                    />

                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div>
                    <label
                        className="
            mb-2
            block
            text-[10px]
            font-black
            uppercase
            tracking-widest
            text-gray-200
        "
                    >
                        {isEn ? "Phone Number" : "Nomor Telepon"}
                    </label>

                    <input
                        type="text"
                        value={data.phone}
                        onChange={(e) => setData("phone", e.target.value)}
                        className="
            w-full
            rounded-2xl
            border
            border-gray-300
            bg-white
            px-6
            py-4
            font-bold
            text-black
        "
                    />
                </div>
                <div className="mt-4">
                    <InputLabel htmlFor="password" value="Password" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        autoComplete="new-password"
                        onChange={(e) => setData("password", e.target.value)}
                        required
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>
                <div>
                    <label
                        className="
            mb-2
            block
            text-[10px]
            font-black
            uppercase
            tracking-widest
            text-gray-200
        "
                    >
                        {isEn ? "Position" : "Jabatan"}
                    </label>

                    <input
                        type="text"
                        value={data.position}
                        onChange={(e) => setData("position", e.target.value)}
                        className="
            w-full
            rounded-2xl
            border
            border-gray-300
            bg-white
            px-6
            py-4
            font-bold
            text-black
        "
                    />

                    <InputError message={errors.position} className="mt-2" />
                </div>
                <div className="mt-4">
                    <InputLabel
                        htmlFor="password_confirmation"
                        value="Confirm Password"
                    />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="mt-1 block w-full"
                        autoComplete="new-password"
                        onChange={(e) =>
                            setData("password_confirmation", e.target.value)
                        }
                        required
                    />

                    <InputError
                        message={errors.password_confirmation}
                        className="mt-2"
                    />
                </div>

                <div className="text-center">
                    <p
                        className="
            text-[10px]
            font-black
            uppercase
            tracking-widest
            text-gray-200
        "
                    >
                        {isEn
                            ? "Already have an account?"
                            : "Sudah memiliki akun?"}
                    </p>

                    <Link
                        href={route("login")}
                        className="
            mt-2
            block
            text-xs
            font-black
            uppercase
            italic
            text-yellow-500
        "
                    >
                        {isEn ? "LOGIN TO DIGESTEX →" : "MASUK KE DIGESTEX →"}
                    </Link>
                </div>

                <div className="pt-4">
                    <button
                        type="submit"
                        disabled={processing}
                        className="
            w-full
            rounded-2xl
            bg-gradient-to-r
            from-amber-500
            to-yellow-500
            px-6
            py-4
            text-sm
            font-black
            uppercase
            tracking-wider
            text-black
            shadow-lg
            transition
            hover:scale-[1.02]
            hover:shadow-amber-500/30
            disabled:cursor-not-allowed
            disabled:opacity-50
        "
                    >
                        {processing
                            ? isEn
                                ? "CREATING ACCOUNT..."
                                : "MEMBUAT AKUN..."
                            : isEn
                              ? "CREATE ACCOUNT"
                              : "BUAT AKUN"}
                    </button>
                </div>
            </form>
            <div
                className="
        rounded-2xl
        border
        border-emerald-500/20
        bg-emerald-500/10
        p-5
    "
            >
                <div className="font-black text-emerald-400">
                    {isEn ? "WHAT YOU'LL GET" : "YANG AKAN ANDA DAPATKAN"}
                </div>

                <div className="mt-4 space-y-2 text-sm text-white">
                    <div>✓ Digital Company Passport™</div>
                    <div>✓ Executive Dashboard™</div>
                    <div>✓ Smart Business Matching™</div>
                    <div>✓ Build My Supply Chain™</div>
                    <div>✓ Executive AI Insight™</div>
                </div>
            </div>
        </GuestLayout>
    );
}
