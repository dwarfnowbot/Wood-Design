import { Link } from "react-router-dom";
import type { ReactNode } from "react";

type CommonProps = {
  children: ReactNode;
  variant?: "primary" | "secondary" | "outline" | "ghost";
  className?: string;
};

type LinkButtonProps = CommonProps & {
  to: string;
  href?: never;
  onClick?: never;
};

type AnchorButtonProps = CommonProps & {
  href: string;
  to?: never;
  onClick?: never;
  target?: string;
  rel?: string;
};

type ClickButtonProps = CommonProps & {
  onClick: () => void;
  to?: never;
  href?: never;
  type?: "button" | "submit";
};

type ButtonProps = LinkButtonProps | AnchorButtonProps | ClickButtonProps;

const variantClasses: Record<string, string> = {
  primary:
    "bg-espresso text-ivory hover:bg-walnut-dark border border-espresso hover:border-walnut-dark",
  secondary:
    "bg-transparent text-ivory border border-ivory/70 hover:bg-ivory hover:text-espresso",
  outline:
    "bg-transparent text-espresso border border-espresso/30 hover:border-espresso hover:bg-espresso hover:text-ivory",
  ghost: "bg-bronze text-espresso hover:bg-bronze-light border border-bronze",
};

const base =
  "inline-flex items-center justify-center gap-2 rounded-full px-7 py-3 text-sm tracking-wide uppercase font-medium transition-all duration-300";

export default function Button(props: ButtonProps) {
  const { children, variant = "primary", className = "" } = props;
  const classes = `${base} ${variantClasses[variant]} ${className}`;

  if ("to" in props && props.to) {
    return (
      <Link to={props.to} className={classes}>
        {children}
      </Link>
    );
  }

  if ("href" in props && props.href) {
    return (
      <a href={props.href} target={props.target} rel={props.rel} className={classes}>
        {children}
      </a>
    );
  }

  const { onClick, type } = props as ClickButtonProps;
  return (
    <button type={type ?? "button"} onClick={onClick} className={classes}>
      {children}
    </button>
  );
}
